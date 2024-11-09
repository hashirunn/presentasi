<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
});

Route::group(['middleware' => ['role:pustakawan']], function () {
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
    Route::patch('/books/update', [BookController::class, 'update'])->name('books.update');
});

require __DIR__.'/auth.php';
