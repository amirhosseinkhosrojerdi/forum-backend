<?php

namespace Tests\Feature\API\V01\Thread;

use App\Models\Answer;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Summary of can_get_all_answers_list
     * @return void
     */
    public function test_can_get_all_answers_list()
    {
        $response = $this->getJson(route('answers.index'));

        $response->assertSuccessful();
    }
    
    /**
     * Summary of test_answer_should_be_validated_before_storing
     * @return void
     */
    public function test_answer_should_be_validated_before_storing()
    {
        $response = $this->postJson(route('answers.store'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // Unprocessable Entity
        $response->assertJsonValidationErrors(['content', 'thread_id']);
    }

    /**
     * Summary of test_can_submit_answer_for_thread
     * @return void
     */
    public function test_can_submit_answer_for_thread()
    {
        // Ensure the user is authenticated
        Sanctum::actingAs(User::factory()->create());

        // Create a thread to associate the answer with
        $thread = Thread::factory()->create();

        // Simulate an authenticated user
        $response = $this->postJson(route('answers.store'), [
            'content' => 'This is a test answer',
            'thread_id' => $thread->id, // Assuming thread with ID exists
        ]);

        $response->assertStatus(Response::HTTP_CREATED); // Created
        $response->assertJson(['message' => 'Answer created successfully']);
        $this->assertTrue($thread->answers()->where('content', 'This is a test answer')->exists());
    }

    /**
     * Summary of test_update_answer_should_be_validated
     * @return void
     */
    public function test_update_answer_should_be_validated()
    {
        $answer = Answer::factory()->create();
        $response = $this->putJson(route('answers.update', [$answer]), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY); // Unprocessable Entity
        $response->assertJsonValidationErrors(['content']);
    }

    /**
     * Summary of test_can_update_own_answer_of_thread
     * @return void
     */
    public function test_can_update_own_answer_of_thread()
    {
        // Ensure the user is authenticated
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create an answer to update
        $answer = Answer::factory()->create([
            'content' => 'This is an existing answer',
            'user_id' => $user->id, // Ensure the answer belongs to the authenticated user
        ]);

        // Simulate an authenticated user
        $response = $this->putJson(route('answers.update', [$answer]), [
            'content' => 'This is a test answer',
        ]);
        
        $response->assertStatus(Response::HTTP_OK); // OK
        $response->assertJson(['message' => 'Answer updated successfully']);
        
        $answer->refresh(); // Refresh the answer instance to get updated data
        $this->assertEquals($answer->content, 'This is a test answer');
    }

    /**
     * Summary of test_can_delete_own_answer
     * @return void
     */
    public function test_can_delete_own_answer()
    {
        // Ensure the user is authenticated
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create an answer to delete
        $answer = Answer::factory()->create([
            'user_id' => $user->id, // Ensure the answer belongs to the authenticated user
        ]);

        // Simulate an authenticated user
        $response = $this->deleteJson(route('answers.destroy', [$answer]));

        $response->assertStatus(Response::HTTP_OK); // OK
        $response->assertJson([
            'message' => 'Answer deleted successfully'
        ]);

        $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists()); // Ensure the answer still exists in the database
    }
}
