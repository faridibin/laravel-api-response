<?php

namespace Faridibin\LaravelApiJsonResponse\Exceptions;

class ApiResponseErrorException extends \Exception
{
    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $statusCode = 500;

    /**
     * Constructs the Exception.
     *
     * @param null|string $message The Exception message to throw.
     * @param int|null $code The Exception Response status code.
     * @param \Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @return mixed
     */
    public function __construct($message = null, $statusCode = 500, $errors = [], $previous = null)
    {
        if ($message === null) {
            $this->message = "An internal server error occured.";
        }

        $this->setStatusCode($statusCode);
    }

    /**
     * Sets the HTTP status code to be used for the response.
     *
     * @param  int  $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }
}
