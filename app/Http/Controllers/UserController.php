<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index() {
        // $users = User::get();
        // return $users;
        return UserResource::collection(User::with(['todos.categories'])->get());
    }

    public function update($id, Request $request) {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'old_password' => 'required',
            'password' => 'required'
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $validated['name'] ?? $user->name,
            'username' => $validated['username'] ?? $user->username,
            'email' => $validated['email'] ?? $user->email,
            'old_password' => $validated['old_password'],
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
}
