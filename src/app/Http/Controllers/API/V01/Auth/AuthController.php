<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Register New User
     * @method Post
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        // Validate From Inputs
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);

        // Insert User Into Database
        $user = resolve(UserRepository::class)->create($request);
        
        // Assign Role Based on Email
        // If the email matches the default super admin email, assign 'Super Admin' role,
        // otherwise assign 'User' role.
        // This logic is based on the configuration setting for the default super admin email.
        $defaultSuperAdminEmail = config('permission.default_super_admine_email');
        $user->email === $defaultSuperAdminEmail ? $user->assignRole('Super Admin') : $user->assignRole('User');
        
        return response()->json([
            'message' => 'User created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Login User
     * @method Post
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // Validate From Inputs
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check User Credentials
        if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json([Auth::user(), Response::HTTP_OK]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    /**
     * Login User
     * @method Get
     * @return JsonResponse
     */
    public function user()
    {
        $data = [
            'user' => Auth::user(),
            'notifications' => Auth::user()->unreadNotifications,
        ];
        return response()->json([$data, Response::HTTP_OK]);
    }

    /**
     * Logout User
     * @method Post
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK);
    }
}
