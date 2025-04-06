<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'author_id' => 'required',
            'status' => 'required|in:Not started , In progress,Completed'
        ]);
    }

    public function getTodo() {
        $users = Todo::with(['user', 'categories'])->get();
        return $users;
    }
}
