<?php

namespace Faridibin\LaravelApiResponse\Providers;

use Faridibin\LaravelApiResponse\Classes\ApiResponse;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(LARAVEL_API_RESPONSE_KEY, function () {
            return new ApiResponse();
        });

        $this->publishes([
            __DIR__ . '/../../config/api-response.php' => config_path('api-response.php'),
        ], LARAVEL_API_RESPONSE_CONFIG);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/api-response.php', LARAVEL_API_RESPONSE_CONFIG);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [ApiResponse::class];
    }
}
