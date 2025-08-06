<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Thread;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AnswerRepository
{
    /**
     * Get all answers.
     *
     * @return Collection
     */
    public function getAllAnswers()
    {
        // Retrieve all answers ordered by the latest
        return Answer::query()->latest()->get();
    }
    
    /**
     * Store a new answer.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // store the answer in the database with the provided content and thread_id
        Thread::find($request->thread_id)->answers()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(), // Assuming the user is authenticated
        ]);
    }

    /**
     * Update an existing answer.
     *
     * @param Request $request
     * @param Answer $answer
     * @return void
     */
    public function update(Request $request, Answer $answer)
    {
        // Update the answer in the database with the provided content and thread_id
        $answer->update([
            'content' => $request->input('content'),
        ]);
    }

    /**
     * Delete an answer.
     *
     * @param Answer $answer
     * @return void
     */
    public function destroy(Answer $answer)
    {
        // Delete the answer from the database
        $answer->delete();
    }
}