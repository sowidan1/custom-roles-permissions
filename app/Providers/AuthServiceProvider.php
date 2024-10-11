<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    protected $policies = [

    ];
    public function boot()
    {
        Gate::define('create_post', function (User $user) {
            return $user->role == 'super_admin'
                ? Response::allow()
                : Response::deny('You do not have permission to perform this action');
        });

        Gate::define('update_post', function (User $user, Post $post) {
            return $user->id == $post->user_id
                ? Response::allow()
                : Response::deny('You do not have permission to perform this action');
        });

    }
}
