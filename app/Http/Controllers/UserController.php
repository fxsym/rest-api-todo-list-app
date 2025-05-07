<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function getHallo() {
        $hallo = "Hallo";
        return $hallo;
    }
    public function index() {
        return UserResource::collection(User::with(['todos.categories'])->get());
    }

    public function getUser() {
        $user = Auth::user();
        return $user;
    }

    public function show($id) {
        return response()->json([
            'message' => 'Data successfully found',
            'data' => new UserResource(User::with(['todos.categories'])->findOrFail($id))
        ], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource(User::findOrFail($user->id))
        ], 201);
    }

    public function update($id, Request $request) {
        $validated = $request->validate([
            'name' => 'nullable',
            'username' => 'nullable',
            'email' => 'nullable|email'
        ]);
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);


        $data = [
            'name' => $validated['name'] ?? $user->name,
            'username' => $validated['username'] ?? $user->username,
            'email' => $validated['email'] ?? $user->email
        ];

        $user->update($data);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => new UserResource(User::findOrFail($user->id))
        ], 200);
    }

    public function changePassword($id, Request $request) {
        $validated = $request->validate([
            'old_password' => 'nullable',
            'password' => 'nullable'
        ]);
        $user = User::findOrFail($id);
        Gate::authorize('update', $user);

        $data = [
            'old_password' => $validated['old_password'] ?? $user->password,
            'password' => Hash::make($validated['password']) ?? $user->password
        ];

        if (!Hash::check($data['old_password'], $user->password) ) {
            throw ValidationException::withMessages([
                'message' => ['Ur old password is wrong'],
            ]);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => new UserResource(User::findOrFail($user->id))
        ], 200);
    }

    public function checkUsername(Request $request) {
        $reqUsername = $request->reqUsername;
        $takenUsername = User::pluck('username')->toArray();
        
        return response()->json([
            'taken' => in_array($reqUsername, $takenUsername)
        ]);
    }
}
