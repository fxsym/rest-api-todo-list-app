<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'nullable|in:Not started,In progress,Completed'
        ]);

        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'author_id' => $validated['author_id'],
            'status' => $validated['status'] ?? 'Not started'
        ]);

        if (isset($validated['categories'])) {
            $todo->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => $todo->load(['user' ,'categories'])
        ], 201);
    }

    public function destroy($id) {
        $todo = Todo::with('categories')->findOrFail($id);
        $todo->categories()->detach();
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully',
        ]);
    }

    public function getTodo() {
        $users = Todo::with(['user', 'categories'])->get();
        return $users;
    }
}
