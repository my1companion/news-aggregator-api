<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ArticleController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']);

});
Route::post('password/forgot', [PasswordResetController::class, 'sendResetLink']);
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');



Route::middleware('auth:sanctum')->prefix('articles')->group(function () {


    // Fetch paginated articles with search functionality
    Route::get('/', [ArticleController::class, 'index']);

    // Fetch a single article's details by ID
    Route::get('{id}', [ArticleController::class, 'show']);

    Route::get('/search', [ArticleController::class, 'search']);
    

});
