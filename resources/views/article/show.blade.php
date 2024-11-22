@extends('main')
@section('content')
@use('App\Models\User', 'User')
<div class="card" style="width: 18rem;">
  <div class="card-body">
    <p class="card-text">Author: {{$user->name}}</p>
    <h5 class="card-title">{{ $article->name }}</h5>
    <h6 class="card-subtitle mb-2 text-body-secondary">{{ $article->date }}</h6>
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

@endsection