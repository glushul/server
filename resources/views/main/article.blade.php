@extends('main.layout')
@section('content')

<table class="table table-striped table-hover table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Date</th>
      <th scope="col">Title</th>
      <th scope="col">Short Desc</th>
      <th scope="col">Desc</th>
      <th scope="col">Image</th>
    </tr>
  </thead>
  <tbody>
    @foreach($articles as $article)
    <tr>
      <th scope="row">{{ $article->date }}</th>
      <td>{{$article->name}}</td>
      <td>{{$article->shortDesc}}</td>
      <td>{{$article->desc}}</td>
      <td>
        @if($article->preview_image)
          <a href="full_image/{{$article->full_image}}" target="_blank">
            <img src="{{$article->preview_image}}" alt="Preview" style="max-width: 100px; max-height: 60px; object-fit: cover; border-radius: 4px;">
          </a>
        @else
          <span class="text-muted">No image</span>
        @endif
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

@endsection
