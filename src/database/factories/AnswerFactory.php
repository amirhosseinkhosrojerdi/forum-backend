<?php

namespace Database\Factories;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->realText(200), // Generate realistic text content
            'user_id' => User::factory(), // Associate with a user
            'thread_id' => Thread::factory(), // Associate with a thread
        ];
    }
}
