<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
</head>

<body class="antialiased">
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" aria-current="page"
                                    data-bs-toggle="dropdown" aria-expanded="false" href="#">
                                    Notifications {{ auth()->user()->unreadNotifications->count() }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @foreach (auth()->user()->unreadNotifications as $notification)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('article.show', ['article' => $notification->data['article']['name'], 'notify'=>$notification->id]) }}">
                                                {{ $notification->data['article']['name'] }}:
                                                {{ $notification->data['comment_name'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endauth
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/article">Articles</a>
                        </li>
                        @can('create')
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/article/create">Create article</a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/comment/show">Comments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/about">О нас</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  active" href="/contacts">Контакты</a>
                        </li>
                        @guest
                            <li class="nav-item">
                                <a href="/auth/signup" class="btn btn-outline-success me-3">SignUp</a>
                            </li>
                            <li class="nav-item">
                                <a href="/auth/login" class="btn btn-outline-success">SignIn</a>
                            </li>
                        @endguest
                        @auth
                            <li class="nav-item">
                                <a href="/auth/logout" class="btn btn-outline-success">LogOut</a>
                            </li>
                        @endauth
                    </ul>
                </div>
        </nav>
    </header>
    <main>
        <div id="app">
            <App />
        </div>
        <div class="container mt-3">
            @yield('content')
        </div>
    </main>
</body>

</html>