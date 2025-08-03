<?php

namespace Tests\Unit\API\V01\Channel;

use App\Models\Channel;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class ChannelControllerTest extends TestCase
{
    /**
     * Test that all channels list should be accessible.
     *
     * @return void
     */
    public function test_all_channels_list_should_be_accessible()
    {
        // Simulate a request to the getChannels method
        $response = $this->get(route('channel.all'));

        // Assert that the response is successful
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that channel creating should be validated.
     *
     * @return void
     */ 
    public function test_channel_creatign_should_be_validated()
    {
        $response = $this->postJson(route('channel.create'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that create new channel.
     *
     * @return void
     */
    public function test_create_new_channel()
    {
        $response = $this->postJson(route('channel.create'), [
            "name" => "Laravel"
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test that channel update should be validated.
     *
     * @return void
     */
    public function test_channel_update_should_be_validated()
    {
        $response = $this->json('PUT', route('channel.update'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that channel update .
     *
     * @return void
     */
    public function test_channel_update()
    {
        $channel = Channel::factory()->create([
            'name' => 'Laravel'
        ]);
        $response = $this->json('PUT', route('channel.update'), [
            'id' => $channel->id,
            'name' => 'Vuejs'
        ]);

        $updatedChannel = Channel::find($channel->id);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('Vuejs', $updatedChannel->name);
    }

    /**
     * Test that channel delete should be validated.
     *
     * @return void
     */
    public function test_channel_delete_should_be_validated()
    {
        $response = $this->json('DELETE', route('channel.delete'), []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that channel delete.
     *
     * @return void
     */
    public function test_channel_delete()
    {
        $channel = Channel::factory()->create();
        $response = $this->json('DELETE', route('channel.delete'), [
            'id' => $channel->id
        ]);
        
        $response->assertStatus(Response::HTTP_OK);
    }
}
