<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Resources\TodoResource;
use Illuminate\Support\Facades\Gate;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Todo::class);
        $userId = $request->user()->id;

        $limit = $request->query('limit');

        $query = Todo::with(['categories', 'user'])
            ->where('author_id', $userId)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            $query->limit($limit);
        }

        $todos = $query->get();

        return response()->json([
            'message' => 'Data successfully found',
            'data' => TodoResource::collection($todos)
        ], 200);
    }


    public function show(Request $request)
    {
        // $users = Todo::with(['user', 'categories'])->findOrFail($id);
        // return $users;
        // return new TodoResource(Todo::with(['categories', 'user'])->findOrFail($id));
        $title = $request->query('title');
        $userId = $request->user()->id;

        $todo = Todo::with(['categories', 'user'])
            ->where('author_id', $userId)
            ->where('title', 'LIKE', '%' . $title . '%')
            ->first();

        Gate::authorize('view', $todo);

        return response()->json([
            'message' => 'Data successfully found',
            'data' => new TodoResource($todo)
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'nullable|in:Not started,In progress,Completed'
        ]);

        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'author_id' => $request->user()->id,
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

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'nullable|in:Not started,In progress,Completed'
        ]);
        $todo = Todo::findOrFail($id);
        Gate::authorize('update', $todo);

        $data = [
            'title' => $validated['title'] ?? $todo->title,
            'description' => $validated['description'] ?? $todo->description,
            'status' => $validated['status'] ?? $todo->status
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

    public function destroy($id)
    {
        $todo = Todo::with('categories')->findOrFail($id);
        Gate::authorize('delete', $todo);

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
