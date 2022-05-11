<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthenticationController extends Controller
{

    public function register(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors(),
            ], 400);
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        return response([
            'message' => 'User added successfully.',
            'data' => [
                'user' => $user
            ]
        ], 200);
    }

    public function login(Request $request)
    {
        $input = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check Email
        $user = User::where('email', $input['email'])->first();

        // Check Password
        if (!$user || !Hash::check($input['password'], $user->password)) {
            return response([
                'message' => "Your password is incorrect or this account doesn't exist. Please try again."
            ], 401);
        }

        $token = $user->createToken('todo_API')->plainTextToken;

        return response([
            'data' => [
                'user' => $user,
                'token' => $token,
            ]
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => "User logout."
        ], 200);
    }
}
