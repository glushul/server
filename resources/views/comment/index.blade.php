@extends('main.layout')

@section('content')

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Comments moderation</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Author</th>
                    <th>Article</th>
                    <th>Text</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ $comment->user->name ?? 'Unknown' }}</td>
                    <td>
                        <a href="{{ route('article.show', $comment->article_id) }}">
                            {{ $comment->article->title ?? 'Deleted article' }}
                        </a>
                    </td>
                    <td style="max-width: 350px;">
                        {{ Str::limit($comment->text, 100) }}
                    </td>
                    <td class="text-end">
                        @if(!$comment->accept)
                            <a href="{{ url('/comment/accept/'.$comment->id) }}" 
                               class="btn btn-success btn-sm">
                                Accept
                            </a>
                        @else
                            <a href="{{ url('/comment/reject/'.$comment->id) }}" 
                               class="btn btn-warning btn-sm">
                                Reject
                            </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center my-4">
    {{ $comments->links('pagination::bootstrap-4') }}
</div>
@endsection
