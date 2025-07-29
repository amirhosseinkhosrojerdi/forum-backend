<?php

namespace Tests\Unit\API\V01\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the registration endpoint validates input.
     *
     * @return void
     */
    public function test_register_should_be_validate()
    {
        $response = $this->postJson(route('auth.register'));
        $response->assertStatus(422);
    }

    /**
     * Test that a new user can register.
     *
     * @return void
     */
    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('auth.register'), [
            "name" => "Amirhossein",
            "email" => "info@gmail.com",
            "password" => "123456"
        ]);
        $response->assertStatus(201);
    }

    /**
     * Test that the login endpoint validates input.
     *
     * @return void
     */
    public function test_login_should_be_validate()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(422);   
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
        $response->assertStatus(200);
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
        $response->assertStatus(200);
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
        $response->assertStatus(200);
    }
}
