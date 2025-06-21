<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
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

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'User register successfully, please verify your email',
            'data' => new UserResource(User::findOrFail($user->id))
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'message' => 'Login Succesfully',
            'token' => $token,
            'user' => new UserResource(User::with(['todos.categories'])->findOrFail($user->id))
        ], 200);
    }

    public function logout(Request $request)
    {
        // $request->user()->currentAccessToken()->delete(); //Revoke the token that was used to authenticate the current request
        $request->user()->tokens()->delete();  //Revoke all tokens...
        return response()->json([
            'message' => 'Logout Succes'
        ]);
    }
}
