@extends('main')
@section('content')

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $error)
    <li class="alert alert-danger">{{$error}}</li>
  @endforeach
  </ul>
@endif

<form action="/comment/{{ $comment->id }}/update" method="POST">
  @csrf
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{$comment->name}}">
  </div>
  <div class="mb-3">
    <label for="desc" class="form-label">Description</label>
    <textarea name="desc" id="desc" class="form-control">{{$comment->desc}}</textarea>
  </div>
  <input type="hidden" name="article_id" value="{{ $comment->article_id }}">
  <input type="hidden" name="user_id" value="{{ $comment->user_id }}">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection