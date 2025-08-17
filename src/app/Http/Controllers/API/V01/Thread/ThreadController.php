<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ThreadController extends Controller
{
    /**
     * ThreadController constructor.
     *
     * This constructor applies the user_block middleware to all methods in this controller,
     * except for the index and show methods.
     */
    public function __construct()
    {
        // Apply the user_block middleware to all methods in this controller
        $this->middleware('user_block')->except(['index', 'show']);
    }

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

    /**
     * Update a thread.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function update(Request $request, Thread $thread)
    {
        $request->has('best_answer_id') ?
        $request->validate([
            'best_answer_id' => 'required',
        ])
        :
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'channel_id' => 'required'
        ])
        ;
 
        if(Gate::forUser(auth()->user())->allows('user-thread', $thread)){
            resolve(ThreadRepository::class)->update($request, $thread);

            return response()->json([
                'message' => 'Thread update Successfuly.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Thread update access denied.'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Destroy a thread.
     *
     * @param $slug
     * @return JsonResponse
     */
    public function destroy(Thread $thread)
    {
        if(Gate::forUser(auth()->user())->allows('user-thread', $thread)){
            resolve(ThreadRepository::class)->destroy($thread);

            return response()->json([
                'message' => 'Thread destroy Successfuly.'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Thread destroy access denied.'
        ], Response::HTTP_FORBIDDEN);
    }
}
