<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }

    // Каналы уведомлений
    public function via($notifiable)
    {
        return ['database'];
    }

    // Данные для записи в таблицу
    public function toDatabase($notifiable)
    {
        return [
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
            'message' => 'Добавлена новая статья: ' . $this->article->title,
        ];
    }
}
