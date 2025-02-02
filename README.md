# Laravel API Response

A Laravel package that provides a standardized, consistent way to format API responses with proper status codes, messages, and error handling.

## Features

-   Modern PHP 8.0+ implementation with type hints and return types
-   Consistent JSON response structure
-   Automatic handling of Laravel Models, Collections, and Paginated responses
-   Configurable resource naming for collections
-   Comprehensive exception handling
-   Debug mode with stack traces
-   Support for custom headers and messages

## Installation

Install the package via composer:

```bash
composer require faridibin/laravel-api-response
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="api-response-config"
```

### Configuration Options

The `config/api-response.php` file includes:

```php
return [
    // Exception handlers
    'exceptions' => [
        ModelNotFoundException::class => [
            'setStatusCode' => 404,
            'setModelNotFoundMessage' => 'Resource not found'
        ],
        ValidationException::class => function($exception, $handler) {
            $handler
                ->setStatusCode(422)
                ->setMessage('Validation failed')
                ->mergeErrors($exception->errors());
        }
    ],

    // Enable stack traces in local environment
    'trace' => env('APP_ENV') === 'local',

    // Use plural resource names for collections
    'resource_name' => true,
];
```

## Basic Usage

Add the middleware to your API routes in `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'api' => [
        \Faridibin\LaravelApiResponse\Http\Middleware\EnsureApiResponse::class,
    ],
];
```

### Response Structure

The package provides a consistent response structure:

```json
{
	"data": {},
	"message": "Optional message",
	"errors": [],
	"success": true,
	"status": "success",
	"status_code": 200,
	"status_text": "OK"
}
```

## Examples

### Returning Models

#### Single Model

```php
public function show(User $user)
{
    return $user;
}
```

Response:

```json
{
	"data": {
		"user": {
			"id": 1,
			"name": "Crystal Farrell",
			"email": "berenice.bednar@example.org",
			"email_verified_at": "2025-02-02T02:20:17.000000Z",
			"created_at": "2025-02-02T02:20:17.000000Z",
			"updated_at": "2025-02-02T02:20:17.000000Z"
		}
	},
	"errors": [],
	"success": true,
	"status": "success",
	"status_code": 200,
	"status_text": "OK"
}
```

### Collection Handling

#### Basic Collection

```php
public function index()
{
    return User::all();
}
```

Response includes automatically pluralized resource name:

```json
{
	"data": {
		"users": [
			{
				"id": 1,
				"name": "Crystal Farrell",
				"email": "berenice.bednar@example.org",
				"email_verified_at": "2025-02-02T02:20:17.000000Z",
				"created_at": "2025-02-02T02:20:17.000000Z",
				"updated_at": "2025-02-02T02:20:17.000000Z"
			}
		]
	},
	"success": true,
	"status": "success",
	"status_code": 200,
	"status_text": "OK"
}
```

#### Pagination

```php
public function index()
{
    return User::paginate();
}
```

## Upgrading from laravel-json-response

Key changes when upgrading from the previous version:

1. Namespace Change:

    - Old: `Faridibin\LaravelJsonResponse`
    - New: `Faridibin\LaravelApiResponse`

2. Middleware:

    - Old: `OutputJsonResponse`
    - New: `EnsureApiResponse`

3. Trait:

    - Old: `HasJson`
    - New: `HasApiResponse`

4. Method Changes:

    ```php
    // Old
    json_response()->error('message');

    // New
    $this->setMessage('message')->mergeErrors(['error']);
    ```

## Troubleshooting

### Common Issues

1. Response Not Formatting

    ```php
    // Ensure middleware is registered correctly in Kernel.php
    protected $middlewareGroups = [
        'api' => [
            \Faridibin\LaravelApiResponse\Http\Middleware\EnsureApiResponse::class,
        ],
    ];
    ```

2. Resource Names Not Working

    ```php
    // Verify config/api-response.php has:
    'resource_name' => true,
    ```

3. Exception Handler Not Working

    ```php
    // Check exception configuration:
    'exceptions' => [
        YourException::class => [
            'setStatusCode' => 404,
            'setMessage' => 'Custom message'
        ]
    ]
    ```

4. Stack Traces Not Showing
    - Ensure your environment is set to 'local'
    - Check `trace` config is enabled

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.
