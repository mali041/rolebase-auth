<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::controller(UserController::class)->group(function () {
    Route::post('/signup', 'signup')->name('signup');
    Route::post('/signin', 'signin')->name('signin');
    Route::get('/signout', 'signout')->name('signout');
});

Route::view('/signin', 'auth.signin')->name('signin.view');
Route::view('/signup', 'auth.signup')->name('signup.view');

Route::middleware(['admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::get('/load-categories-data', [CategoryController::class, 'loadCategoriesData'])->name('load-categories-data');
    Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts');
    Route::get('admin/load-posts-data', [PostController::class, 'loadPostsData'])->name('load-posts-data');
});

Route::middleware(['user'])->group(function () {
    Route::get('/user/categories', [CategoryController::class, 'index'])->name('user.categories');
    Route::resource('user/posts', PostController::class);
    Route::get('user/load-posts-data', [PostController::class, 'loadPostsData'])->name('load-posts-data');
});
