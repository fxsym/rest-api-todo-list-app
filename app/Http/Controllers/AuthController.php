<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request) {
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
            'token' => $token
        ], 200);
     
    }

    public function logout(Request $request) {
        // $request->user()->currentAccessToken()->delete(); //Revoke the token that was used to authenticate the current request
        $request->user()->tokens()->delete();  //Revoke all tokens...
        return response()->json([
            'message' => 'Logout Succes'
        ]);
    }

    // public function checkTokenIsValid(Request $request)
    // {
    //     // Chek user
    //     if(!$request->user()){
    //         return false;
    //     }

    //     $idUser = $request->user()->id();

    //     PersonalAccessToken::where()->value('token')

    //     if(token === $request->token){
    //         retujrn true
    //     } else {
    //         return false
    //     }
    // }
}
