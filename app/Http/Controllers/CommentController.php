<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(){
        $this->authorize('crud-article');
        $comments = Comment::latest()->paginate(10);
        return view('comment.index', ['comments'=>$comments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $comment = new Comment;
        $comment->text = $request->text;
        $comment->user_id = auth()->id();
        $comment->article_id = $request->article_id;
        $comment->accept = false;

        $comment->save();
         return redirect()->route('article.show', $request->article_id)->with('message', "Comment add succesful and enter for moderation");
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        $this->authorize('crud-comment', $comment);
        return view("comment.edit", ['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('crud-comment', $comment);
        $comment->text = $request->text;

        $comment->save();
        return redirect()->route('article.show', $comment->article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('crud-comment', $comment);
        $comment->delete();
        return redirect()->route('article.show', $comment->article);
    }

    public function accept(Comment $comment){
        $this->authorize('crud-article');
        $comment->accept = true;
        $comment->save();
        Cache::forget("article:show:{$comment->article_id}");
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        $this->authorize('crud-article');
        $comment->accept = false;
        $comment->save();
        Cache::forget("article:show:{$comment->article_id}");
        return redirect()->route('comment.index');
    }
}
