<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\ArticleMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Article;

class VeryLongJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $article;
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(env('MAIL_TO_ADDRESS'))->send(new ArticleMail($this->article));
    }
}
