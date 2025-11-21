<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Article;
use App\Models\Comment;
use App\Jobs\VeryLongJob;
use App\Events\NewArticleEvent;
use App\Notifications\NewArticleNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('track.views')->only('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->get('page', 1);
        $perPage = 15;

        $articles = Cache::remember("articles-page-{$page}", 60, function () use ($perPage) {
            return Article::latest()->paginate($perPage);
        });

        return view('article.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('crud-article');
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('crud-article');

        $request->validate([
            'date_public'=>'required',
            'title'=>'required',
            'text'=>'required'   
        ]);

        $article = new Article();
        $article->date_public = $request->date_public;
        $article->title = $request->title;
        $article->text = $request->text;
        $article->user_id = auth()->id();

        if ($article->save()) {
            Cache::flush();

            VeryLongJob::dispatch($article);
            event(new NewArticleEvent($article));

            $users = User::where('id', '!=', auth()->id())->get();
            Notification::send($users, new NewArticleNotification($article));
        }

        return redirect()->route('article.index')->with('message', 'Статья добавлена и уведомления отправлены.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $articleWithComments = Cache::rememberForever("article-{$article->id}", function () use ($article) {
            return $article->load(['comments' => function($query) {
                $query->where('accept', true);
            }, 'comments.user']);
        });

        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications
                 ->where('data.article_id', $article->id)
                 ->markAsRead();
        }

        return view('article.show', ['article' => $articleWithComments, 'comments' => $articleWithComments->comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        $this->authorize('crud-article');
        return view("article.edit", ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('crud-article');

        $request->validate([
            'date_public'=>'required',
            'title'=>'required',
            'text'=>'required'   
        ]);

        $article->update($request->only(['date_public', 'title', 'text']));

        Cache::forget("article-{$article->id}");
        Cache::flush();

        return redirect()->route('article.show', $article)->with('message', 'Статья обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('crud-article');
        $article->delete();

        Cache::forget("article-{$article->id}");
        Cache::flush();

        return redirect()->route('article.index')->with('message', 'Статья удалена.');
    }
}
