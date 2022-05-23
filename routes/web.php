<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::view('login', 'auth.login');
    Route::post('login', [\App\Http\Controllers\LoginController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

    Route::get('/authors', [App\Http\Controllers\AuthorController::class, 'index'])->name('authors.index');
    Route::delete('/authors/{author}', [App\Http\Controllers\AuthorController::class, 'destroy'])->name('authors.destroy');
    Route::get('/authors/{author}', [App\Http\Controllers\AuthorController::class, 'show'])->name('authors.show');

    Route::delete('/books/{book}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
    Route::view('/books/create', 'books.create')->name('books.create');
    Route::post('/books', [\App\Http\Controllers\BookController::class, 'store'])->name('books.store');
});
