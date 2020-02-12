<?php

namespace App\Providers;

use App\Repositories\VideoCategoryRepositoryEloquent;
use App\VideoCategory;
use Illuminate\Support\ServiceProvider;

class VideoCategoryRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\VideoCategoryRepositoryInterface', function () {
            return new VideoCategoryRepositoryEloquent(new VideoCategory());
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
