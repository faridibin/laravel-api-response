<?php

use Faridibin\LaravelApiResponse\Exceptions\ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return [

    /*
    |--------------------------------------------------------------------------
    | Data Format
    |--------------------------------------------------------------------------
    |
    | All APIs have a response data format. This defines just a way
    | the API handles the interaction between data generation and data request,
    | typically between server and client.
    |
    | Supported: "json", "xml", "yaml"
    |
    */

    'data_format' => 'json',

    /*
    |--------------------------------------------------------------------------
    | URI Cases
    |--------------------------------------------------------------------------
    |
    | All API responses have resources. This defines just a way
    | of naming the resources to resemble natural language while avoiding
    | spaces, apostrophes, and other exotic characters.
    |
    | If you have multiple naming conventions in your tables or models
    | you may need to configure this option to specify which one you
    | as a default.
    |
    | Supported: "camel", "snake", "spinal"
    |
    */

    'uri_case' => 'snake',

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Here you may specify if you want to use the resource name, which
    | will be used instead of the "data", for the paginated items or all of the
    | model's results from the database.
    |
    */

    'resource_name' => false,

    /*
    |--------------------------------------------------------------------------
    | Authorization Tokens
    |--------------------------------------------------------------------------
    |
    | By default, Passport uses auto-incrementing primary keys when assigning
    | IDs to clients. However, if Passport is installed using the provided
    | --uuids switch, this will be set to "true" and UUIDs will be used.
    |
    |
    | Supported: "header", "response"
    |
    */

    'token' =>  [
        'include' => false,
        'scope' => 'header',
    ],

    /*
    |--------------------------------------------------------------------------
    | Handling Exceptions
    |--------------------------------------------------------------------------
    |
    | By default, all exceptions are handled by the default exception handler.
    | You may specify a different exception handler for each exception
    | as required, but they're a perfect start for most applications.
    |
    | You may set 'stack_trace' to true to include the stack trace in the
    | ressponse when an exception is thrown in debug mode. Stack traces are
    | not included in production and for any exception that is
    | 'exceptions.handlers'.
    |
    */

    'exceptions' => [

        'stack_trace' => true,

        'handlers' => [
            AuthenticationException::class => [
                'setMessage' => 'Unauthenticated.',
                'setStatusCode' => Response::HTTP_UNAUTHORIZED
            ],

            ModelNotFoundException::class => [
                'setMessage' => 'No query results for model found!',
                'setStatusCode' => Response::HTTP_NOT_FOUND
            ],

            ValidationException::class => function (ValidationException $e, ExceptionHandler $handler) {
                $handler
                    ->mergeErrors($e->errors())
                    ->setStatusCode($e->status ?? Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        ],
    ]
];
