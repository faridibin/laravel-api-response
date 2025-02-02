<?php

use Faridibin\LaravelApiResponse\Interfaces\HandlesResponse;
use Illuminate\Auth\{Access\AuthorizationException, AuthenticationException};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

return [
    /*
    |--------------------------------------------------------------------------
    | Response Data Format
    |--------------------------------------------------------------------------
    |
    | All APIs have a response data format. This defines just a way
    | the API handles the interaction between data generation and data request,
    | typically between server and client.
    |
    | Supported: "json", "xml", "yaml"
    |
    */

    'format' => 'json',

    /*
    |--------------------------------------------------------------------------
    | Response Case
    |--------------------------------------------------------------------------
    |
    | This option allows you to set the case of the response data.
    | The supported cases are 'camel', 'snake'.
    |
    */

    'case' => 'snake',

    /*
    |--------------------------------------------------------------------------
    | Use Resource Name
    |--------------------------------------------------------------------------
    |
    | This option allows you to set the resource name in the response data.
    | When set to true, the API will include the resource name in the response data.
    |
    */

    'resource_name' => true,

    /*
    |--------------------------------------------------------------------------
    | The Exceptions To Handle
    |--------------------------------------------------------------------------
    |
    | This is a list of exceptions that the API should handle.
    | When an exception is thrown, the API will catch it and return a response
    | based on the exception.
    |
    */

    'exceptions' => [

        /**
         * The AuthenticationException is thrown when the user is not authenticated.
         */
        AuthenticationException::class => [
            'setMessage' => 'Unauthenticated.',
            'setStatusCode' => 401,
        ],

        /**
         * The AuthorizationException is thrown when the user is not authorized to perform an action.
         */
        AuthorizationException::class => [
            'setMessage' => 'This action is unauthorized.',
            'setStatusCode' => 403,
        ],

        /**
         * The ModelNotFoundException is thrown when a model is not found.
         */
        ModelNotFoundException::class => [
            'setModelNotFoundMessage' => 'No query results for model.',
            'setStatusCode' => 404,
        ],

        /**
         * The ValidationException is thrown when a validation fails.
         */
        ValidationException::class => function (ValidationException $e, HandlesResponse $json): void {
            $json
                ->setMessage($e->getMessage())
                ->mergeErrors($e->errors())
                ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
        },

        /**
         * The Exception is thrown when an error occurs while processing a request.
         */
        \Exception::class => [
            'setMessage' => 'An error occurred while processing your request.',
            'setStatusCode' => 500,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Response Data Tracing
    |--------------------------------------------------------------------------
    |
    | This option allows you to trace the response data.
    | When set to true, the API will trace the response data.
    |
    */

    'trace' => (bool) env('APP_DEBUG', false),
];
