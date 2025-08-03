<?php

namespace Tests\Feature\API\V01\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthControllerTest extends TestCase
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
     * Test that the registration endpoint validated.
     *
     * @return void
     */
    public function test_register_should_be_validated()
    {
        $response = $this->postJson(route('auth.register'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that a new user can register.
     *
     * @return void
     */
    public function test_new_user_can_register()
    {
        $this->registerRolesAndPermissions();
        $response = $this->postJson(route('auth.register'), [
            "name" => "Amirhossein",
            "email" => "info@gmail.com",
            "password" => "123456"
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test that the login endpoint validated.
     *
     * @return void
     */
    public function test_login_should_be_validated()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);   
    }

    /**
     * Test that a user can log in with valid credentials.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create();
        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password' // Assuming the factory sets a default password
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }
    
    /**
     * Test that a show user info can log in.
     *
     * @return void
     */
    public function test_show_user_info_can_login()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('auth.user'));
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test that a user can log out.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('auth.logout'));
        $response->assertStatus(Response::HTTP_OK);
    }
}
