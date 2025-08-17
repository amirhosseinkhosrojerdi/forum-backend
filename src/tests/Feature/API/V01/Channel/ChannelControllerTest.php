<?php

namespace Tests\Feature\API\V01\Channel;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function PHPUnit\Framework\assertEquals;

class ChannelControllerTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Set up a roles and permissions for testing.
     *
     * @return void
     */
    public function registerRolesAndPermissions(): void
    {
        foreach (config('permission.default_roles') as $key => $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach (config('permission.default_permissions') as $key => $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
    }

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
        $this->registerRolesAndPermissions();
        
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
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
        $this->registerRolesAndPermissions();
        
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
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
        $this->registerRolesAndPermissions();
        
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
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
        $this->registerRolesAndPermissions();

        $channel = Channel::factory()->create([
            'name' => 'Laravel'
        ]);
        
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
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
        $this->registerRolesAndPermissions();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
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
        $this->registerRolesAndPermissions();

        $channel = Channel::factory()->create();
        
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $user->givePermissionTo('Manage Channels');
        $response = $this->json('DELETE', route('channel.delete'), [
            'id' => $channel->id
        ]);
        
        $response->assertStatus(Response::HTTP_OK);

        $this->assertTrue(Channel::where('id', $channel->id)->count() === 0);
    }
}
