<?php

namespace Faridibin\LaravelApiJsonResponse;

use Illuminate\Support\ServiceProvider;

class ApiJsonResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishes package's configuration file to the application's config directory.
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path(LARAVEL_API_JSON_RESPONSE_CONFIG . '.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ApiResponse::class, function () {
            return new ApiResponse(request());
        });

        // Merges package configuration file with the application's published copy.
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            LARAVEL_API_JSON_RESPONSE_CONFIG
        );
    }
}
