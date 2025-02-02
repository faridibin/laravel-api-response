<?php

namespace Faridibin\LaravelApiResponse\Interfaces;

interface HandlesResponse
{
    /**
     * Sets the HTTP status code to be used for the response.
     * @param  int  $statusCode
     */
    public function setStatusCode(int $statusCode);

    /**
     * Set the response message.
     * @param string $message
     */
    public function setMessage(string $message);

    /**
     * Merge the response errors.
     * @param array $errors
     */
    public function mergeErrors(array $errors);

    /**
     * Set the response headers.
     * @param array $headers
     */
    public function setHeaders(array $headers);
}
