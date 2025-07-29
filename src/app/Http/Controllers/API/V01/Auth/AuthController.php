<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register New User
     * @method Post
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function register(Request $request): JsonResponse
    {
        // Validate From Inputs
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);

        // Insert User Into Database
        resolve(UserRepository::class)->create($request);

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    /**
     * Login User
     * @method Post
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        // Validate From Inputs
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check User Credentials
        if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json([Auth::user(), 200]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Login User
     * @method Get
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(): JsonResponse
    {
        return response()->json([Auth::user(), 200]);
    }

    /**
     * Logout User
     * @method Post
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
