<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Article;
use App\Models\User;
use App\Jobs\VeryLongJob;
use App\Events\NewArticleEvent;
use App\Notifications\NewArticleNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use AuthorizesRequests;

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

        $articles = Cache::remember("articles:index:page:{$page}", 3600, function () use ($perPage) {
            return Article::with('user')->latest()->paginate($perPage);
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
            'date_public' => 'required|date',
            'title' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $article = Article::create([
            'date_public' => $request->date_public,
            'title' => $request->title,
            'text' => $request->text,
            'user_id' => auth()->id(),
        ]);

        Cache::forget("articles:index:page:1");

        VeryLongJob::dispatch($article);
        event(new NewArticleEvent($article));

        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new NewArticleNotification($article));

        return redirect()->route('article.index')->with('message', 'Статья добавлена и уведомления отправлены.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $articleWithComments = Cache::rememberForever("article:show:{$article->id}", function () use ($article) {
            return $article->load([
                'user',
                'comments' => function ($query) {
                    $query->where('accept', true)->with('user');
                }
            ]);
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
        return view('article.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('crud-article');

        $request->validate([
            'date_public' => 'required|date',
            'title' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        $article->update($request->only(['date_public', 'title', 'text']));

        Cache::forget("article:show:{$article->id}");

        return redirect()->route('article.show', $article)->with('message', 'Статья обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('crud-article');
        $article->delete();

        Cache::forget("article:show:{$article->id}");
        return redirect()->route('article.index')->with('message', 'Статья удалена.');
    }
}