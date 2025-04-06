<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::post('/user', [UserController::class, 'store']);
Route::patch('/user/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::patch('/user/changepass/{id}', [UserController::class, 'changePassword'])->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');

Route::get('/todos', [TodoController::class, 'index']);
Route::get('/todo/{id}', [TodoController::class, 'show']);
Route::post('/todo', [TodoController::class, 'store'])->middleware('auth:sanctum');
Route::patch('/todo/{id}', [TodoController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->middleware('auth:sanctum');


Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});