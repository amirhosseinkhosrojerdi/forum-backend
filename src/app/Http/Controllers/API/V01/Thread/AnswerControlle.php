<?php

namespace App\Http\Controllers\API\V01\Thread;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\User;
use App\Repositories\AnswerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AnswerControlle extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Logic to retrieve and return a list of answers
        $answers = resolve(AnswerRepository::class)->getAllAnswers(); 
        
        // Return the list of answers as a JSON response
        return response()->json($answers, HttpFoundationResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Ensure the user is authenticated
        Sanctum::actingAs(User::factory()->create());

        // Validate the request
        $this->validate($request, [
            'content' => 'required',
            'thread_id' => 'required',
        ]);

        // Store the answer using the repository
        resolve(AnswerRepository::class)->store($request);

        // Return a success response
        return response()->json([
            'message' => 'Answer created successfully',
        ], HttpFoundationResponse::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Answer  $answer
     * @return JsonResponse
     */
    public function update(Request $request, Answer $answer)
    {
        // Validate the request
        $this->validate($request, [
            'content' => 'required',
        ]);

        // Check if the user is authorized to update the answer
        if(Gate::forUser(auth()->user())->allows('user-answer', $answer)) {
            // Update the answer using the repository
            resolve(AnswerRepository::class)->update($request, $answer);

            // Return a success response
            return response()->json([
                'message' => 'Answer updated successfully',
            ], HttpFoundationResponse::HTTP_OK);
        }

        // If the user is not authorized, return a forbidden response
        return response()->json([
            'message' => 'Answer update failed: Unauthorized',
        ], HttpFoundationResponse::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Answer  $answer
     * @return JsonResponse
     */
    public function destroy(Answer $answer)
    {
        // Check if the user is authorized to update the answer
        if(Gate::forUser(auth()->user())->allows('user-answer', $answer)) {
            // Delete the answer using the repository
            resolve(AnswerRepository::class)->destroy($answer);

            // Return a success response
            return response()->json([
                'message' => 'Answer deleted successfully',
            ], HttpFoundationResponse::HTTP_OK);
        }

        // If the user is not authorized, return a forbidden response
        return response()->json([
            'message' => 'Answer deletion failed: Unauthorized',
        ], HttpFoundationResponse::HTTP_FORBIDDEN);
    }
}
