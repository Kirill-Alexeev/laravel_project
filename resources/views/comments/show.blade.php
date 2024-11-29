@extends('main')
@section('content')
@use('App\Models\User', 'User')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Author</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($comments as $comment)
            <tr>
                <th scope="row">{{$comment->created_at}}</th>
                <td>{{$comment->name}}</td>
                <td>{{$comment->desc}}</td>
                <td>{{User::findOrFail($comment->user_id)->name}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection