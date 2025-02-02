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

### Examples

#### Returning a Model

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

#### Returning a Collection|Pagination

```php
public function index()
{
    return User::all();
}
```

Response:

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
			},
			...
        ]
	},
	"success": true,
	"status": "success",
	"status_code": 200,
	"status_text": "OK"
}
```

```php
public function index()
{
    return User::paginate();
}
```

Response:

```json
{
	"data": {
		"current_page": 1,
		"first_page_url": "https://package-maker.test/api/users?page=1",
		"from": 1,
		"last_page": 20,
		"last_page_url": "https://package-maker.test/api/users?page=20",
		"links": [
			{ "url": null, "label": "&laquo; Previous", "active": false },
			...
		],
		"next_page_url": "https://package-maker.test/api/users?page=2",
		"path": "https://package-maker.test/api/users",
		"per_page": 5,
		"prev_page_url": null,
		"to": 5,
		"total": 100,
		"users": [
			{
				"id": 1,
				"name": "Crystal Farrell",
				"email": "berenice.bednar@example.org",
				"email_verified_at": "2025-02-02T02:20:17.000000Z",
				"created_at": "2025-02-02T02:20:17.000000Z",
				"updated_at": "2025-02-02T02:20:17.000000Z"
			},
			...
		]
	},
	"errors": [],
	"success": true,
	"status": "success",
	"status_code": 200,
	"status_text": "OK"
}
```

## Exception Handling

Configure exception handlers in `config/api-response.php`:

```php
return [
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
];
```

## Available Methods

### Error Handling

-   `mergeErrors(array $errors)`: Add multiple errors
-   `setStatusCode(int $code)`: Set HTTP status code
-   `setModelNotFoundMessage(string $message)`: Set the Model Not Found message

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.
