<?php

namespace App\Providers;

use App\Repositories\UserRepositoryEloquent;
use App\User;
use Illuminate\Support\ServiceProvider;

class UserRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\UserRepositoryInterface', function () {
            return new UserRepositoryEloquent(new User());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
