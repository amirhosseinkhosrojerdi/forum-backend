<?php

namespace App\Http\Controllers\API\V01\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{
    /**
     * Get a list of channels.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllChannelsList()
    {
        // Logic to retrieve channels
        $all_channels = resolve(ChannelRepository::class)->all();
        return response()->json($all_channels, Response::HTTP_OK);
    }

    /**
     * Create a new channel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createNewChannel(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required'
        ]);
        
        // Logic to create a new channel
        resolve(ChannelRepository::class)->create($request);

        return response()->json([
            'message' => 'Channel Created Successfuly'
        ], Response::HTTP_CREATED);
    }


    /**
     * Update a channel
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateChannel(Request $request){
        // Validate the request
        $request->validate([
            'name' => 'required'
        ]);

        // Logic to edite a channel
        resolve(ChannelRepository::class)->update($request);

        return response()->json([
            'message' => 'Channel edited Successfuly'
        ], Response::HTTP_OK);
    }

    /**
     * Delete a channel
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteChannel(Request $request)
    {
        // Validate the request
        $request->validate([
            'id' => 'required'
        ]);

        // Logic to delete a channel
        resolve(ChannelRepository::class)->delete($request);

        return response()->json([
            'message' => 'Channel deleted Successfuly'
        ], Response::HTTP_OK);
    }
}
