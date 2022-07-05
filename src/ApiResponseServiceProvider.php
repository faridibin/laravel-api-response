<?php

namespace Faridibin\LaravelApiResponse;

use Faridibin\LaravelApiResponse\Http\XmlResponse;
use Faridibin\LaravelApiResponse\Http\YamlResponse;
use Illuminate\Support\Facades\Response;
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
            return new ApiResponse($app->get('request'));
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

        /*
        |--------------------------------------------------------------------------
        | Register the API response macros.
        */

        Response::macro('xml', function ($data = [], $status = 200, array $headers = [], $options = 0) {
            return new XmlResponse($data, $status, $headers, $options);
        });

        Response::macro('yaml', function ($data = [], $status = 200, array $headers = [], $options = 0) {
            return new YamlResponse($data, $status, $headers, $options);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [ApiResponse::class];
    }
}
