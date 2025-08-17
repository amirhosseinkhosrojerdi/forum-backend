<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Find a user by ID.
     *
     * @param int $id
     * @return User|null
     */
    public function find($id)
    {
        return User::find($id);
    }

    /**
     * Create a new user.
     * 
     * @param Request $request
     * @return User
     */
    public function create(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * Get the leaderboard of users ordered by score.
     * 
     * @return LengthAwarePaginator
     */
    public function leaderboards()
    {
        return User::query()->orderByDesc('score')->paginate(20);
    }

    /**
     * Check if the authenticated user is blocked.
     *
     * @return bool
     */
    public function isBlocked()
    {
        return (bool) auth()->user()->is_blcocked;
    }
}