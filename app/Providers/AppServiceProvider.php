<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Comment;
use App\Http\Middleware\TrackArticleViews;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('track.views', function () {
            return new TrackArticleViews();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, $ability) {
            if ($user->isModerator()) {
                return true;
            }
        });

        Gate::define('crud-article', function (User $user) {
            return $user->isModerator();
        });

        Gate::define('crud-comment', function (User $user, Comment $comment) {
            return $user->isModerator() || $user->id === $comment->user_id;
        });
    }
}
