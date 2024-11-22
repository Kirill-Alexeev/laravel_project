@extends('main')
@section('content')

@if ($errors->any())
  <ul>
    @foreach ($errors->all() as $error)
    <li class="alert alert-danger">{{$error}}</li>
  @endforeach
  </ul>
@endif

<form action="/article/{{ $article->id }}" method="POST">
  @csrf
  @method("PUT")
  <div class="mb-3">
    <label for="date" class="form-label">Date</label>
    <input type="date" class="form-control" id="date" name="date" value="{{$article->date}}">
  </div>
  <div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{$article->name}}">
  </div>
  <div class="mb-3">
    <label for="desc" class="form-label">Desc</label>
    <textarea name="desc" id="desc" class="form-control">{{$article->desc}}</textarea>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection