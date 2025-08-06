<?php

namespace Tests\Feature\API\V01\Thread;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ThreadControllerTest extends TestCase
{
    /**
     * Test that all threads list should be accessible.
     *
     * @return void
     */
    public function test_all_threads_list_should_be_accessible()
    {
        // Simulate a request to the index method
        $response = $this->get(route('threads.index'));

        // Assert that the response is ok
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that thread should be accessible by slug.
     *
     * @return void
     */
    public function test_thread_should_be_accessible_by_slug()
    {
        // Simulate a request to the show method
        $thread = Thread::factory()->create();
        $response = $this->get(route('threads.show', [$thread->slug]));

        // Assert that the response is ok
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that thread create should be validated.
     *
     * @return void
     */
    public function test_thread_create_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());

        // Simulate a request to the store method
        $response = $this->postJson(route('threads.store', []));

        // Assert that the response is unprocessable
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that thread create.
     *
     * @return void
     */
    public function test_thread_create()
    {
        Sanctum::actingAs(User::factory()->create());

        // Simulate a request to the store method
        $response = $this->postJson(route('threads.store', [
            'title' => 'Foo',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
        ]));

        // Assert that the response is created
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test that thread update should be validated.
     *
     * @return void
     */
    public function test_thread_update_should_be_validated()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();

        // Simulate a request to the update method
        $response = $this->putJson(route('threads.update', [$thread]), []);

        // Assert that the response is unprocessable
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that thread can update.
     *
     * @return void
     */
    public function test_thread_can_update()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create([
            'title' => 'Foo',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
            'user_id' => $user->id,
        ]);

        // Simulate a request to the update method
        $response = $this->putJson(route('threads.update', [$thread]), [
            'title' => 'Bar',
            'content' => 'Bar',
            'channel_id' => Channel::factory()->create()->id,
        ])->assertSuccessful();

        $thread->refresh();

        $this->assertSame('Bar', $thread->title);
    }

    /**
     * Test that thread can best answer id update.
     *
     * @return void
     */
    public function test_thread_can_best_answer_id_update()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);

        // Simulate a request to the update method
        $response = $this->putJson(route('threads.update', [$thread]), [
            'best_answer_id' => 1,
        ])->assertSuccessful();

        $thread->refresh();

        $this->assertSame(1, $thread->best_answer_id);
    }

    /**
     * Test that thread can destroy.
     *
     * @return void
     */
    public function test_thread_can_destroy()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create([
            'user_id' => $user->id,
        ]);

        // Simulate a request to the store method
        $response = $this->delete(route('threads.destroy', [$thread->id]));

        // Assert that the response is ok
        $response->assertStatus(Response::HTTP_OK);
    }
}
