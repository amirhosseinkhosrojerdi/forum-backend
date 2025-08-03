<?php

namespace App\Repositories;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChannelRepository
{
    /**
     * All channels list
     */
    public function all()
    {
        return Channel::all();
    }

    /**
     * Create new channel
     * 
     * @param Request $request
     */
    public function create(Request $request)
    {
        Channel::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
    }

    /**
     * Update new channel
     * 
     * @param Request $request
     */
    public function update(Request $request)
    {
        Channel::find($request->id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);
    }

    /**
     * Delete a channel
     * 
     * @param Request $request
     */
    public function delete(Request $request)
    {
        Channel::destroy($request->id);
    }
}