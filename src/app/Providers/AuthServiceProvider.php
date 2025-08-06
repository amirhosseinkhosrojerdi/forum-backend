<?php

namespace App\Providers;

use App\Models\Answer;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Thread;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define a gate for super admin users
        Gate::before(function ($user) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Define a gate for user-specific threads
        Gate::define('user-thread', function(User $user, Thread $thread){
            return $user->id === $thread->user_id;
        });

        // Define a gate for user-specific answers
        Gate::define('user-answer', function(User $user, Answer $answer){
            return $user->id === $answer ->user_id;
        });
    }
}
