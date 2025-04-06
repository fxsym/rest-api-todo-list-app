<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        // $users = User::get();
        // return $users;
        return UserResource::collection(User::with(['todos.categories'])->get());
    }
}
