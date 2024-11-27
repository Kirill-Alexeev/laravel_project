@extends('main')
@section('content')
@use('App\Models\User', 'User')

@if (session('status')) 
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li class="alert alert-danger">{{$error}}</li>
        @endforeach
    </ul>
@endif

<div class="card" style="width: 70rem; margin-bottom: 20px;">
  <div class="card-body">
    <p class="card-text">Author: {{$user->name}}</p>
    <div class="card-header">
      <h5 class="card-title">{{ $article->name }}</h5>
      <h6 class="card-subtitle mb-2 text-body-secondary">{{ $article->date }}</h6>
    </div>
    <p class="card-text">{{ $article->desc }}</p>

    <div class="d-flex justify-content-end gap-3">
      <a href="/article/{{$article->id}}/edit" class="btn btn-primary">Edit article</a>
      <form action="/article/{{$article->id}}" method="POST">
        @method("DELETE")
        @csrf
        <button type="submit" class="btn btn-danger">Delete article</button>
      </form>
    </div>
  </div>
</div>

<form action="/comment" method="POST"  style="margin-bottom: 20px;">
  @csrf
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="mb-3">
    <label for="desc" class="form-label">Description</label>
    <input type="text" class="form-control" id="desc" name="desc" value="">
  </div>
  <input type="hidden" name="article_id" value="{{ $article->id }}">
  <button type="submit" class="btn btn-primary">Save</button>
</form>

@foreach ($comments as $comment)
  <div class="card" style="margin-bottom: 15px; width: 50rem;">
    <div class="card-header">
    <p class="card-text">{{ $comment->user->name }}</p>
    </div>
    <div class="card-body">
    <h3 class="card-text">{{ $comment->name }}</h3>
    <p class="card-text">{{ $comment->desc }}</p>
    </div>
    @can('update_comment', $comment)
    <div class="d-flex justify-content-center gap-3 mb-2" >
      <a href="/comment/{{$comment->id}}/edit" class="btn btn-primary">Edit comment</a>
      <form action="/comment/{{$comment->id}}/delete" method="GET">
        @csrf
        <button type="submit" class="btn btn-danger">Delete comment</button>
      </form>
    </div>
    @endcan
  </div>
@endforeach

@endsection