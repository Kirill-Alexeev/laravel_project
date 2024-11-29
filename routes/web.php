<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Authentithicate
Route::get('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::get('/auth/login', [AuthController::class, 'login'])->name('login');
Route::post('/auth/signin', [AuthController::class, 'authenticate']);
Route::get('/auth/logout', [AuthController::class, 'logout']);

// Article
Route::resource('/article', ArticleController::class)->middleware('auth:sanctum');

// Comment
Route::controller(CommentController::class)->prefix('/comment')->middleware('auth:sanctum')->group(function () {
    Route::post('', 'store');
    Route::get('/{id}/edit', 'edit');
    Route::post('/{comment}/update', 'update');
    Route::get('/{id}/delete', 'delete');
    Route::get('/show', 'show');
});

// Main
Route::get('/', [MainController::class, 'index']);
Route::get('/galery/{img}/{name}', function ($img, $name) {
    return view('main.galery', ['img' => $img, 'name' => $name]);
});
Route::get('/about', function () {
    return view('main.about');
});
Route::get('/contacts', function () {
    $data = [
        'city' => 'Moscow',
        'street' => 'Semenovskaya',
        'house' => 38,
    ];
    return view('main.contact', ['data' => $data]);
});
