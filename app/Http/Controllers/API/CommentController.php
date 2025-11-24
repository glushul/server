<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('user', 'article')->latest()->paginate(10);
        return response()->json(['comments' => $comments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
            'article_id' => 'required|exists:articles,id',
        ]);

        $comment = new Comment();
        $comment->text = $request->text;
        $comment->user_id = auth()->id();
        $comment->article_id = $request->article_id;
        $comment->accept = false;

        $comment->save();

        return response()->json([
            'message' => 'Comment added successfully and sent for moderation.',
            'comment' => $comment->load('user')
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('crud-comment', $comment);

        $request->validate([
            'text' => 'required|string|max:2000',
        ]);

        $comment->text = $request->text;
        $comment->save();

        return response()->json([
            'message' => 'Comment updated.',
            'comment' => $comment->load('user')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('crud-comment', $comment);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted.'
        ]);
    }

    /**
     * Accept a comment (moderation).
     */
    public function accept(Comment $comment)
    {
        $this->authorize('crud-article');
        $comment->accept = true;
        $comment->save();

        return response()->json([
            'message' => 'Comment accepted.'
        ]);
    }

    /**
     * Reject a comment (moderation).
     */
    public function reject(Comment $comment)
    {
        $this->authorize('crud-article');
        $comment->accept = false;
        $comment->save();

        return response()->json([
            'message' => 'Comment rejected.'
        ]);
    }
}