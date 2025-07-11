<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Simple Get
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
Route::get('/books/search', [BookController::class, 'search']);
Route::patch('/borrows/{borrow}/confirm', [BorrowController::class, 'confirm'])->name('borrows.confirm');
Route::patch('/borrows/{borrow}/return', [BorrowController::class, 'return'])->name('borrows.return');
Route::patch('/borrows/{borrow}/overdue', [BorrowController::class, 'overdue'])->name('borrows.overdue');
Route::patch('/borrows/{borrow}/archive', [BorrowController::class, 'archive'])->name('borrows.archive');


// Route Resource
Route::resource('books', BookController::class);
Route::resource('categories', CategoryController::class);
Route::resource('dashboard', DashboardController::class);
Route::resource('borrows', BorrowController::class);
Route::resource('users', UserController::class);
