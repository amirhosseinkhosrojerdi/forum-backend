<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\Thread;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SubscribeController extends Controller
{
    /**
     * SubscribeController constructor.
     *
     * This constructor applies the user_block middleware to all methods in this controller,
     * except for the index and show methods.
     */
    public function __construct()
    {
        // Apply the user_block middleware to all methods in this controller
        $this->middleware('user_block');
    }

    /**
     * Subscribe to a thread.
     *
     * @param Thread $thread
     * @return JsonResponse
     */
    public function subscribe(Thread $thread)
    {
        Subscribe::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
        ]);

        return response()->json([
            'message' => 'Subscribed successfully',
        ], Response::HTTP_OK);
    }

    /**
     * Unsubscribe from a thread.
     *
     * @param Thread $thread
     * @return JsonResponse
     */
    public function unSubscribe(Thread $thread)
    {
        Subscribe::query()->where([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
        ])->delete();

        return response()->json([
            'message' => 'UnSubscribe successfully',
        ], Response::HTTP_OK);
    }
}
