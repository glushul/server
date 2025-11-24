<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\CommentController;

Route::prefix('v1')->group(function () {

    // Публичные эндпоинты (без авторизации)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{article}', [ArticleController::class, 'show']);

    Route::get('/comments', [CommentController::class, 'index']);

    // Защищённые эндпоинты (требуют Sanctum-токен)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // Статьи
        Route::post('/articles', [ArticleController::class, 'store']);
        Route::put('/articles/{article}', [ArticleController::class, 'update']);
        Route::delete('/articles/{article}', [ArticleController::class, 'destroy']);

        // Комментарии
        Route::post('/comments', [CommentController::class, 'store']);
        Route::put('/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
        Route::post('/comments/{comment}/accept', [CommentController::class, 'accept']);
        Route::post('/comments/{comment}/reject', [CommentController::class, 'reject']);
    });
});