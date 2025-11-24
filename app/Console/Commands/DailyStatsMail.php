<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyStatsMail as DailyStatsMailMailable;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;

class DailyStatsMail extends Command
{
    protected $signature = 'stats:daily';
    protected $description = 'Send daily site usage statistics to moderators';

    public function handle()
    {
        $today = Carbon::today();

        $viewsCount = ArticleView::whereDate('created_at', $today)->count();
        $commentsCount = Comment::whereDate('created_at', $today)->count();

        $moderators = User::where('role_id', 1)->get();
        
        foreach ($moderators as $moderator) {
            Mail::raw("Статистика за " . now()->format('Y-m-d') . ":\n\n- Просмотров статей: $viewsCount\n- Новых комментариев: $commentsCount", function ($message) use ($moderator) {
                $message->to($moderator->email)
                        ->subject('Ежедневная статистика сайта');
            });
        }

        $this->info("Daily stats sent to moderators.");
    }
}
