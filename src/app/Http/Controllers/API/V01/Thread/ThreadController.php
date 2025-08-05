<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    /**
     * Get a list of Threads.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $thread = resolve(ThreadRepository::class)->getAllAvailableThreads();

        return response()->json($thread, Response::HTTP_OK);
    }

    /**
     * Show a list of Threads by slug.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function show($slug)
    {
        $thread = resolve(ThreadRepository::class)->getThreadBySlug($slug);

        return response()->json($thread, Response::HTTP_OK);
    }

    /**
     * Store a thread.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'channel_id' => 'required'
        ]);
 
        resolve(ThreadRepository::class)->store($request);

        return response()->json([
            'message' => 'Thread created Successfuly.'
        ], Response::HTTP_CREATED);
    }
}
