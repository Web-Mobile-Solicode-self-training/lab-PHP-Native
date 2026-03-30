<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\NativeDemoController;

Route::get('/', [BookController::class, 'index'])->name('home');

Route::get('/hello', [NativeDemoController::class, 'hello'])->name('hello');
Route::get('/api-demo', [NativeDemoController::class, 'apiDemo'])->name('api.demo');
