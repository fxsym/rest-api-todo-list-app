<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index() {
        $users = Todo::with(['user', 'categories'])->get();
        return $users;
    }

    public function show($id) {
        $users = Todo::with(['user', 'categories'])->findOrFail($id);
        return $users;
    }

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

    public function update($id, Request $request) {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'author_id' => 'required|exists:users,id',
            'status' => 'nullable|in:Not started,In progress,Completed'
        ]);

        $todo = Todo::findOrFail($id);

        $data = [
            'title' => $validated['title'] ?? $todo->title,
            'description' => $validated['description'] ?? $todo->description,
            'author_id' => $validated['author_id'] ?? $todo->author_id,
            'status' => $validated['status'] ?? 'Not started'
        ];

        $todo->update($data);

        if (isset($validated['categories'])) {
            $todo->categories()->sync($validated['categories']);
        }

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => $todo->load(['user' ,'categories'])
        ]);
    }

    public function destroy($id) {
        $todo = Todo::with('categories')->findOrFail($id);
        $todo->categories()->detach();
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully',
        ]);
    }
}
