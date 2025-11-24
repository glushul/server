<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Jobs\VeryLongJob;
use App\Events\NewArticleEvent;
use App\Notifications\NewArticleNotification;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->get('page', 1);
        $perPage = 15;

        $articles = Cache::remember("articles-page-{$page}", 60, function () use ($perPage) {
            return Article::with('user')->latest()->paginate($perPage);
        });

        return response()->json([
            'articles' => $articles
        ]);
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

        $article = new Article();
        $article->date_public = $request->date_public;
        $article->title = $request->title;
        $article->text = $request->text;
        $article->user_id = auth()->id();

        if ($article->save()) {
            // Инвалидируем только нужный кэш
            Cache::forget("articles-page-*"); // или используй теги, если настроены

            VeryLongJob::dispatch($article);
            event(new NewArticleEvent($article));

            $users = User::where('id', '!=', auth()->id())->get();
            Notification::send($users, new NewArticleNotification($article));

            return response()->json([
                'message' => 'Статья добавлена и уведомления отправлены.',
                'article' => $article->load('user')
            ], 201);
        }

        return response()->json([
            'error' => 'Не удалось сохранить статью.'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $articleWithComments = Cache::remember("article-{$article->id}", 3600, function () use ($article) {
            return $article->load([
                'user',
                'comments' => function($query) {
                    $query->where('accept', true)->with('user');
                }
            ]);
        });

        // Отметить уведомления как прочитанные (если пользователь авторизован)
        $user = auth()->user();
        if ($user) {
            $user->unreadNotifications
                 ->where('data.article_id', $article->id)
                 ->markAsRead();
        }

        return response()->json([
            'article' => $articleWithComments,
            'comments' => $articleWithComments->comments
        ]);
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

        Cache::forget("article-{$article->id}");
        Cache::forget("articles-page-*");

        return response()->json([
            'message' => 'Статья обновлена.',
            'article' => $article->fresh()->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('crud-article');

        $article->delete();

        Cache::forget("article-{$article->id}");
        Cache::forget("articles-page-*");

        return response()->json([
            'message' => 'Статья удалена.'
        ]);
    }
}