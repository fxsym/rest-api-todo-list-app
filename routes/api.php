<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;


//Create User (User not login)
Route::post('/user', [UserController::class, 'store']);
Route::get('/user', [UserController::class, 'getUser'])->middleware('auth:sanctum');

//Get data users or user by id (Every user login)
Route::get('/users', [UserController::class, 'index'])->middleware('auth:sanctum');
Route::get('/user/{id}', [UserController::class, 'show'])->middleware('auth:sanctum');

//Change user profile (User have their profile)
Route::patch('/user/{id}', [UserController::class, 'update'])->middleware('auth:sanctum');

//Change user password (User have their profile)
Route::patch('/user/changepass/{id}', [UserController::class, 'changePassword'])->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'index'])->middleware('auth:sanctum');

//Create new Todo (Every User Login)
Route::post('/todo', [TodoController::class, 'store'])->middleware('auth:sanctum');

//get todo list based on logged in user (User's Todo)
Route::get('/todos', [TodoController::class, 'index'])->middleware('auth:sanctum');

//get todo list based on logged in user search by title (User's Todo)
Route::get('/todo', [TodoController::class, 'show'])->middleware('auth:sanctum');

//Update todo (User have created todo)
Route::patch('/todo/{id}', [TodoController::class, 'update'])->middleware('auth:sanctum');

//Delete todo (User have deteleted todo)
Route::delete('/todo/{id}', [TodoController::class, 'destroy'])->middleware('auth:sanctum');


Route::prefix('auth')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});