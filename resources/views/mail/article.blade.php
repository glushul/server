<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая статья</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .email-card {
            border-radius: 10px;
            overflow: hidden;
        }
        .btn-primary {
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container my-4">
    <div class="card shadow email-card">

        <div class="card-header bg-primary text-white">
            <h4 class="m-0">Новая статья опубликована!</h4>
        </div>

        <div class="card-body">

            <h5 class="card-title">{{ $title }}</h5>

            @if(!empty($author))
                <p class="mb-1"><strong>Автор:</strong> {{ $author }}</p>
            @endif

            @if(!empty($publishedAt))
                <p class="mb-3"><strong>Дата публикации:</strong> {{ $publishedAt }}</p>
            @endif

            @if(!empty($excerpt))
                <p>{{ $excerpt }}</p>
            @endif

            <a href="{{ $url }}" class="btn btn-primary mt-3" target="_blank">
                Читать статью
            </a>

        </div>

        <div class="card-footer text-center text-muted small">
            Это письмо было отправлено автоматически.<br>
            Если вы не хотите получать уведомления — просто удалите его.
        </div>

    </div>
</div>

</body>
</html>
