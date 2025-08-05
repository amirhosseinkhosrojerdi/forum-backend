<?php

namespace App\Repositories;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThreadRepository
{
    /**
     * @return Thread
     */
    public function getAllAvailableThreads()
    {
        // return Thread::where('flag', 1)->latest()->get();;
        return Thread::whereFlag(1)->latest()->get();
    }

    /**
     * @return Thread
     * @param $slug
     */
    public function getThreadBySlug($slug)
    {
        return Thread::whereSlug($slug)->whereFlag(1)->first();
    }

    /**
     * Store a new thread
     * 
     * @param Request $request
     */
    public function store(Request $request)
    {
        Thread::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'channel_id' => $request->input('channel_id'),
            'user_id' => auth()->user()->id,
        ]);
    }
}