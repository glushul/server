<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Article;

class NewArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $articleId;

    public function __construct(Article $article)
    {
        $this->articleId = $article->id;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $article = Article::findOrFail($this->articleId);
        return [
            'article_id' => $article->id,
            'article_title' => $article->title,
            'message' => 'Добавлена новая статья: ' . $article->title,
        ];
    }
}