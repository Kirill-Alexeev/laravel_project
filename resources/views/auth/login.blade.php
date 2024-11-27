@extends('main')
@section('content')

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li class="alert alert-danger">{{$error}}</li>
        @endforeach
    </ul>
@endif

<form action="/auth/signin" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password">
    </div>
    <div class="mb-3">
        <label for="check" class="form-label">Check me out</label>
        <input name="remember" type="checkbox" id="check">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection