<?php

namespace App\Providers;

use App\Repositories\VideoRepositoryEloquent;
use App\Video;
use Illuminate\Support\ServiceProvider;

class VideoRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\VideoRepositoryInterface', function () {
            return new VideoRepositoryEloquent(new Video());
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
