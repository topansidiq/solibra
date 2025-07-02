<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route Resource
Route::resource('books', BookController::class);
Route::resource('categories', CategoryController::class);
Route::resource('dashboard', DashboardController::class);

// Simple Get
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::post('/dashboard/addBook', [DashboardController::class, 'addBook'])->name('dashboard.addBook');
