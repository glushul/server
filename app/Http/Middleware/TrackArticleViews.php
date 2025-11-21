<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ArticleView;
use Symfony\Component\HttpFoundation\Response;

class TrackArticleViews
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->routeIs('article.show')) {
            $article = $request->route('article');
            if ($article) {
                ArticleView::create([
                    'article_id' => $article->id,
                    'url' => $request->url(),
                ]);
            }
        }

        return $next($request);
    }
}
