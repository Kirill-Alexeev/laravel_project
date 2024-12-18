<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APIControllers\AuthController;
use App\Http\Controllers\APIControllers\ArticleController;
use App\Http\Controllers\APIControllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentithicate
Route::get('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/signin', [AuthController::class, 'authenticate']);
Route::get('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum');
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show')->middleware('click');

// Comment
Route::controller(CommentController::class)->prefix('/comment')->middleware('auth:sanctum')->group(function () {
    Route::post('', 'store');
    Route::get('/{id}/edit', 'edit');
    Route::post('/{comment}/update', 'update');
    Route::get('/{id}/delete', 'delete');
    Route::get('/show', 'show')->name('comment.show');
    Route::get('/{comment}/accept', 'accept');
    Route::get('/{comment}/reject', 'reject');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
