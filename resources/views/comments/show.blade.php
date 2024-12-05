@extends('main')
@section('content')
@use('App\Models\User', 'User')
@use('App\Models\Article', 'Article')

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

<table class="table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Article</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Author</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($comments as $comment)
            <tr>
                <th scope="row">{{$comment->created_at}}</th>
                <td>
                    <a href="/article/{{ $comment->article_id }}">{{Article::findOrFail($comment->article_id)->name}}</a>
                </td>
                <td>{{$comment->name}}</td>
                <td>{{$comment->desc}}</td>
                <td>{{User::findOrFail($comment->user_id)->name}}</td>
                <td>
                    @if (!$comment->accept)
                        <a class="btn btn-success" href="/comment/{{ $comment->id }}/accept">Accept</a>
                    @else
                        <a class="btn btn-warning" href="/comment/{{ $comment->id }}/reject">Reject</a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $comments->links() }}

@endsection