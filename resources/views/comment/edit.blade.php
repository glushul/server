@extends('main.layout')

@section('content')
<div class="card mt-4">
    <div class="card-body">
        <h4>Edit Comment</h4>

        <form action="/comment/{{ $comment->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="commentText">Comment Text</label>
                <textarea name="text" id="commentText" class="form-control" rows="3" required>{{ old('text', $comment->text) }}</textarea>
            </div>

            <button type="submit" class="btn btn-success mt-2">Save</button>
            <a href="/article/{{ $comment->article_id }}" class="btn btn-secondary mt-2">← Back</a>
        </form>
    </div>
</div>
@endsection
