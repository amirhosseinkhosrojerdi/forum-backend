<?php

namespace App\Http\Controllers\API\V01\User;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserController extends Controller
{
    /**
     * Display the user notifications.
     *
     * @return JsonResponse
     */
    public function userNotifications()
    {
        // Fetch the authenticated user's unread notifications and paginate them
        return response()->json(auth()->user()->unreadNotifications->paginate(10), HttpFoundationResponse::HTTP_OK);
    }

    /**
     * Display the leaderboards.
     *
     * @return JsonResponse
     */
    public function leaderboards()
    {
        return resolve(UserRepository::class)->leaderboards();
    }
}
