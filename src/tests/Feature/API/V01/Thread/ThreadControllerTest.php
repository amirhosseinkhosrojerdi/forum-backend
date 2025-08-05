<?php

namespace Tests\Feature\API\V01\Thread;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        // Assert that the response is successful
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

        // Assert that the response is successful
        $response->assertStatus(Response::HTTP_OK);
    }
}
