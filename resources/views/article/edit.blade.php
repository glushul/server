@extends('main.layout')
@section('content')

  <ul class="list-group">
    @foreach($errors->all() as $error)
        <li class="list-group-item list-group-item-danger">{{$error}}</li>
    @endforeach
  </ul>

  <form action="/article/{{ $article->id }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title" name="title" value="{{ $article->title }}">
    </div>

    <div class="mb-3">
      <label for="text" class="form-label">Text</label>
      <textarea class="form-control" id="text" name="text" rows="4">{{ $article->text }}</textarea>
    </div>

    <div class="mb-3">
      <label for="date_public" class="form-label">Publication Date</label>
      <input type="date" class="form-control" id="date_public" name="date_public" value="{{ $article->date_public }}">
    </div>

    <button type="submit" class="btn btn-success">Save</button>
    <a href="/article/{{ $article->id }}" class="btn btn-secondary">← Back</a>
  </form>
@endsection
