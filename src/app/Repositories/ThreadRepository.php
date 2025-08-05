<?php

namespace App\Repositories;

use App\Models\Thread;
use Illuminate\Http\Request;

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
}