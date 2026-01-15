<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use App\Models\Article;

class NewArticleEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    public $article;

    /**
     * Create a new event instance.
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('articles');
    }

    /**
     * Данные, которые отправятся на фронтенд
     */
    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id' => $this->article->id,
                'name' => $this->article->name,
            ],
        ];
    }
}
