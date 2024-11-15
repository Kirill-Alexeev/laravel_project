@extends('main')
@section('content')
<table class="table">
    <thead>
        <tr>
            <th scope="col">Date</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">User Id</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($articles as $article)
            <tr>
                <th scope="row">{{$article->date}}</th>
                <td>{{$article->name}}</td>
                <td>{{$article->desc}}</td>
                <td>{{$article->user_id}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection