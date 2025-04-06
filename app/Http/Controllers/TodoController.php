<?php

namespace App\Http\Controllers;

use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index() {
        // $users = Todo::with(['user', 'categories'])->get();
        // return $users;
        // return TodoResource::collection(Todo::with(['categories', 'user'])->get());

        return response()->json([
            'message' => 'Data successfully found',
            'data' => TodoResource::collection(Todo::with(['categories', 'user'])->get())
        ], 200);
    }

    public function show($id) {
        // $users = Todo::with(['user', 'categories'])->findOrFail($id);
        // return $users;
        // return new TodoResource(Todo::with(['categories', 'user'])->findOrFail($id));

        return response()->json([
            'message' => 'Data successfully found',
            'data' => new TodoResource(Todo::with(['categories', 'user'])->findOrFail($id))
        ], 200);
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

        // return response()->json([
        //     'message' => 'Todo created successfully',
        //     'data' => $todo->load(['user' ,'categories'])
        // ], 201);
        // return new TodoResource(Todo::with(['categories', 'user'])->findOrFail($todo->id));

        return response()->json([
            'message' => 'Data added successfully',
            'data' => new TodoResource(Todo::with(['categories', 'user'])->findOrFail($todo->id))
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

        // return response()->json([
        //     'message' => 'Todo created successfully',
        //     'data' => $todo->load(['user' ,'categories'])
        // ]);
        // return new TodoResource(Todo::with(['categories', 'user'])->findOrFail($todo->id));

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => new TodoResource(Todo::with(['categories', 'user'])->findOrFail($todo->id))
        ], 200);
    }

    public function destroy($id) {
        $todo = Todo::with('categories')->findOrFail($id);
        $todo->categories()->detach();
        $todo->delete();

        // return response()->json([
        //     'message' => 'Todo deleted successfully',
        // ]);
        // return new TodoResource(Todo::with(['categories', 'user'])->findOrFail($todo->id));

        return response()->json([
            'message' => 'Data dleted successfully'
        ], 200);
    }
}
