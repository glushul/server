@extends('main.layout')
@section('content')

<table class="table table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Title</th>
      <th scope="col">Text</th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <th scope="row">{{$article['date_public']}}</th>
      <td>
        <a href="article/{{$article['id']}}" class="font-weight-bold text-primary">
          {{$article['title']}}
        </a>
      </td>
      <td>{{$article['text']}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="d-flex justify-content-center my-4">
    {{ $articles->links('pagination::bootstrap-4') }}
</div>
@endsection
