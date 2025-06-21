<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VerificationController;

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware('signed')->name('verification.verify');

Route::post('/email/resend', [VerificationController::class, 'resend'])
    ->middleware(['auth:sanctum']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::get('/test', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'API Test Berhasil!'
    ]);
});

//Get data users or user by id (Every user login)
Route::post('/checkUsername', [UserController::class, 'checkUsername']);

Route::middleware('auth:sanctum', 'verified')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::patch('/user/{id}', [UserController::class, 'update']);
    Route::patch('/user/changepass/{id}', [UserController::class, 'changePassword']);
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/todos', [TodoController::class, 'index']);
    Route::prefix('todo')->group(function () {
        Route::post('create', [TodoController::class, 'store']);
        Route::get('{id}', [TodoController::class, 'show']);
        Route::patch('{id}', [TodoController::class, 'update']);
        Route::delete('{id}', [TodoController::class, 'destroy']);
    });
});
