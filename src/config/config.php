<?php

use Faridibin\LaravelApiResponse\Exceptions\ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return [

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

    // TODO: Pagination; use model name as resource name

    // TODO: Trace; show exception stack trace

    /*
    |--------------------------------------------------------------------------
    | Handling Exceptions
    |--------------------------------------------------------------------------
    |
    | By default, all exceptions are handled by the default exception handler.
    | You may specify a different exception handler for each exception
    | as required, but they're a perfect start for most applications.
    |
    */
    'exceptions' => [
        ModelNotFoundException::class => [
            'setMessage' => 'No query results for model found!',
            'setStatusCode' => Response::HTTP_NOT_FOUND
        ],

        ValidationException::class => function (ValidationException $e, ExceptionHandler $handler) {
            $handler
                ->mergeErrors($e->errors())
                ->setStatusCode($e->status ?? Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    ]
];
