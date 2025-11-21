<head>
    <meta charset="UTF-8">
    <title>MyBlog</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">MyBlog</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Левое меню -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/article">Articles</a>
                </li>
            </ul>

            <!-- Правое меню -->
            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications;
                    @endphp

                    <!-- Уведомления -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Уведомления
                            @if($unreadNotifications->count())
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            @forelse($unreadNotifications as $notification)
                                <li>
                                    <a class="dropdown-item" href="{{ route('article.show', $notification->data['article_id']) }}">
                                        {{ $notification->data['article_title'] }}
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item">Нет новых уведомлений</span></li>
                            @endforelse
                        </ul>
                    </li>

                    <!-- Приветствие -->
                    <li class="nav-item me-3">
                        <span class="nav-link">👋 Hello, <strong>{{ Auth::user()->name }}</strong></span>
                    </li>

                    <!-- Создание статьи -->
                    @can('crud-article')
                        <li class="nav-item me-2">
                            <a href="/article/create" class="btn btn-primary btn-sm">Create Article</a>
                        </li>
                    @endcan

                    <!-- Модерация комментариев для админа -->
                    @can('crud-article')
                        <li class="nav-item me-2">
                            <a href="{{ route('comment.index') }}" class="btn btn-warning btn-sm">Moderate Comments</a>
                        </li>
                    @endcan

                    <!-- Выход -->
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a href="/login" class="btn btn-outline-success btn-sm">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="/register" class="btn btn-outline-info btn-sm">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div id="app"></div>
<div class="container mt-4">
    @yield('content')
</div>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
