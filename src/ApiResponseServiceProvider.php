<?php

namespace Faridibin\LaravelApiResponse;

// use Faridibin\LaravelApiResponse\Classes\ApiResponse;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/api-response.php',
            'api-response'
        );

        // 

        // $this->app->singleton(LARAVEL_API_RESPONSE_KEY, function () {
        //     return new ApiResponse();
        // });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/api-response.php' => config_path('api-response.php'),
        ], 'api-response-config');
    }
}
