<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/user', [UserController::class, 'getUser']);
Route::get('/category', [CategoryController::class, 'getCategory']);
Route::get('/todo', [TodoController::class, 'getTodo']);

Route::post('/todo', [TodoController::class, 'store']);
Route::delete('/todo/{id}', [TodoController::class, 'destroy']);