<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

// Main
Route::get('/', [MainController::class, 'index']);
Route::get('/full_image/{img}', [MainController::class, 'show']);
Route::get('/about', function () {
    return view('main.about');
});
Route::get('/contact', function () {
    $array = [
        'name'=>"Uliana Glushchenko",
        'adress'=>"Malaya Semenovskaya st. 12",
        'phone'=>"+7(918)026-44-74"
    ];
    return view("main.contact", ["contact" => $array]);
});
Route::get('/about', function () {
    return view('main.about');
});

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Article
Route::resource('article', ArticleController::class);

// Comment
Route::resource('comment', CommentController::class);