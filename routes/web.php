<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user', function () {
    return response()->json(['message' => 'User']);
});

Route::get('/api/test', function () {
    return response()->json(['message' => 'Web route API OK']);
});