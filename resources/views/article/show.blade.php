@extends('main.layout')

@section('content')
<div class="card mt-4">
    <div class="card-body">
        <h2 class="card-title">{{ $article->title }}</h2>
        <h6 class="card-subtitle mb-2 text-muted">
            Published: {{ $article->date_public }}
        </h6>
        <p class="card-text">{{ $article->text }}</p>

        @can('crud-article')
        <a href="/article/{{ $article->id }}/edit" class="btn btn-warning">Edit</a>

        <form action="/article/{{ $article->id }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                Delete
            </button>
        </form>
        @endcan

        <a href="/article" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="mt-5">
    <h4>Comments ({{ $article->comments->count() }})</h4>

    @foreach($article->comments as $comment)
    <div class="card mb-3">
        <div class="card-body">
            <p class="mb-1">{{ $comment->text }}</p>
            <small class="text-muted">By {{ $comment->user->name ?? 'Anonymous' }} on {{ $comment->created_at->format('d.m.Y H:i') }}</small>

            @can('crud-comment', $comment)
            <div class="mt-2">
                <a href="/comment/{{ $comment->id }}/edit" class="btn btn-sm btn-outline-warning">Edit</a>

                <form action="/comment/{{ $comment->id }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this comment?')">Delete</button>
                </form>
            </div>
            @endcan
        </div>
    </div>
    @endforeach

    <div class="card mt-4">
        <div class="card-body">
            <form action="/comment" method="POST">
                @csrf
                <input type="hidden" name="article_id" value="{{ $article->id }}">
                <div class="form-group">
                    <label for="commentText">Add a comment</label>
                    <textarea name="text" id="commentText" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
            </form>
        </div>
    </div>
</div>
@endsection
