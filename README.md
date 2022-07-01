# Laravel API Response

## An [Ethereal](https://github.com/Faridibin/ethereal/wiki) Package

Easy way to implement formatted API responses.

#### Format:
```json
{
    "data": {...},
    "errors": [],
    "success": true,
    "status_code": 200,
    "token": null
}
```

## Setup

Install:
```bash
composer require Faridibin/laravel-json-response
```

Add the service provider to your app config:
```php
\Faridibin\LaravelApiResponse\Providers\LaravelApiJsonResponseProvider::class,
```

Add the middleware to your `app\Http\Kernel.php`

Either:

```php
// Formats all responses in json. Catches errors listed in config and JsonResponseErrorExceptions
Faridibin\LaravelApiResponse\Middleware\OutputJsonResponse, 

// Extends the OutputJsonResponse to catch all errors, to keep the JSON output
Faridibin\LaravelApiResponse\Middleware\CatchAllExceptions, 
```

### Config

Publish the config by using the command:
```bash
php artisan vendor:publish
```
