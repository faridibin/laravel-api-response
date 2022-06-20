<?php

namespace Faridibin\LaravelApiResponse;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
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
            __DIR__ . '/config/config.php' => config_path(LARAVEL_API_RESPONSE_CONFIG . '.php'),
        ]);

        $this->app->singleton(LARAVEL_API_RESPONSE_KEY, function ($app) {
            dd($app, $app['request']);
            return OutputApiResponse::class;

            dd("Here", OutputApiResponse::class, $this->app, request(),/*new ApiResponse(request())*/);
            // return new ApiResponse(request());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Merges package configuration file with the application's published copy.
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            LARAVEL_API_RESPONSE_CONFIG
        );
    }


    // /**
    //  * Bootstrap the application services.
    //  *
    //  * @return void
    //  */
    // public function boot()
    // {
    //     $this->publishes([__DIR__ . '/../../../config/config.php' => config_path(LARAVEL_JSON_RESPONSE_CONFIG . '.php')]);

    //     $this->app->singleton(LARAVEL_JSON_RESPONSE_KEY, function () {
    //         return new JsonResponse();
    //     });
    // }

    // /**
    //  * Register the application services.
    //  *
    //  * @return void
    //  */
    // public function register()
    // {
    //     $this->mergeConfigFrom(
    //         __DIR__ . '/../../../config/config.php',
    //         LARAVEL_JSON_RESPONSE_CONFIG
    //     );
    // }
}
