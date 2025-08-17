<?php

namespace Tests\Feature\API\V01\Thread;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Notifications\NewReplySubmitted;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a user can subscribe to a thread.
     *
     * @return void
     */
    public function test_user_can_subscribe_to_thread()
    {
        // Authenticate the user using Sanctum
        Sanctum::actingAs(
            $user = User::factory()->create()
        );
        $thread = Thread::factory()->create();

        // Post a subscription request
        $response = $this->postJson(route('subscribe', ['thread' => $thread]));
        
        // Assert the response is successful
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Subscribed successfully']);
    }

    /**
     * Test if a user can unsubscribe from a thread.
     *
     * @return void
     */
    public function test_user_can_unsubscribe_to_thread()
    {
        // Authenticate the user using Sanctum
        Sanctum::actingAs(
            $user = User::factory()->create()
        );
        $thread = Thread::factory()->create();

        // Post a subscription request
        $response = $this->postJson(route('unSubscribe', ['thread' => $thread]));
        
        // Assert the response is successful
        $response->assertSuccessful();
        $response->assertJson(['message' => 'UnSubscribe successfully']);
    }

    public function test_notification_will_send_to_subscribers_of_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Notification::fake();
        $thread = Thread::factory()->create();

         // Post a subscription request
        $subscribe_response = $this->postJson(route('subscribe', ['thread' => $thread]));
        
        // Assert the response is successful
        $subscribe_response->assertSuccessful();
        $subscribe_response->assertJson(['message' => 'Subscribed successfully']);

        // Create an answer to the thread
        $answer_response = $this->postJson(route('answers.store'), [
            'content' => 'This is an answer to the thread.',
            'thread_id' => $thread->id
        ]);

        // Assert the response is successful
        $answer_response->assertSuccessful();
        $answer_response->assertJson(['message' => 'Answer created successfully']);

        Notification::assertSentTo(
            $user,
            NewReplySubmitted::class
        );
    }
}
