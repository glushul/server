<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view("article.index", ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('crud-article');
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date_public'=>'required',
            'title'=>'required',
            'text'=>'required'   
        ]);

        $article = new Article;
        $article->date_public = $request->date_public;
        $article->title = $request->title;
        $article->text = $request->text;
        $article->user_id = auth()->id();

        $article->save();
        return redirect()->route('article.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article = Article::with('comments.user')->findOrFail($article->id);
        return view("article.show", ['article' => $article]);
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

        $article->date_public = $request->date_public;
        $article->title = $request->title;
        $article->text = $request->text;

        $article->save();
        return redirect()->route('article.show', $article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $this->authorize('crud-article');
        $article->delete();
        return redirect()->route('article.index');
    }
}
