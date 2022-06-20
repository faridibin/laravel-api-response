<?php

namespace Faridibin\LaravelApiResponse\Exceptions;

class ApiResponseErrorException extends \Exception
{
    /**
     * The status code to use for the response.
     *
     * @var int
     */
    public $statusCode = 500;

    /**
     * The errors for the exception.
     *
     * @var array
     */
    protected $errors = [];

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
        } else {
            $this->message = $message;
        }

        $this->setStatusCode($statusCode);
        $this->setErrors($errors);
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

    /**
     * Set the errors to be used for the response.
     *
     * @param  array  $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * Retrieves the errors for the current response.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
