<?php

namespace Faridibin\LaravelJsonResponse;

use Illuminate\Support\ServiceProvider;

class LaravelJsonResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        dd('it works!');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('json', function ($app) {
            return new JsonResponse();
        });
    }
}
