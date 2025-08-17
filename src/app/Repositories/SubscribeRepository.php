<?php

namespace App\Repositories;

use App\Models\Subscribe;
use Illuminate\Support\Collection;

class SubscribeRepository
{
    /**
     * Summary of getNotifiableUsers
     * @param mixed $thread_id
     * @return Collection
     */
    public function getNotifiableUsers($thread_id): Collection
    {
        // Retrieve users subscribed to the thread
        return Subscribe::query()->where('thread_id', $thread_id)->pluck('user_id');
    }
}