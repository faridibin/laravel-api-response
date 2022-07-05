<?php

namespace Faridibin\LaravelApiResponse\Exceptions;

use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

/**
 * ApiResponse represents an HTTP response in JSON format.
 *
 * Note that this class does not force the returned JSON content to be an
 * object. It is however recommended that you do return an object as it
 * protects yourself against XSSI and JSON-JavaScript Hijacking.
 *
 * @see https://github.com/faridibin/laravel-api-response/blob/master/EXAMPLES.md
 *
 * @author Farid Adam <me@faridibin.tech>
 */

class ExceptionHandler extends \Exception
{
    use HasApiResponse;

    /**
     * The exception from \Faridibin\LaravelApiResponse\ApiResponse.
     *
     * @var \Exception
     */
    protected $exception;

    /**
     * The status code to use for the exception.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * The message for the exception.
     *
     * @var string
     */
    protected $message;

    /**
     * The errors for the exception.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Whether or not the handler failed.
     *
     * @var bool
     */
    private $failed = true;

    /**
     * Whether or not the exception is traced.
     *
     * @var bool
     */
    private $traced = true;

    /**
     * Constructor.
     *
     * @param null|\Exception $exception The Exception from \Faridibin\LaravelApiResponse\ApiResponse.
     */
    public function __construct(\Exception $exception = null, int $statusCode = null)
    {
        $this->exceptions = config(LARAVEL_API_RESPONSE_CONFIG . '.exceptions.handlers', []);
        $this->exception = $exception;

        if (isset($statusCode)) {
            $this->setStatusCode($statusCode);
        }

        // Check for exception and handle it.
        if ($this->exception) {
            if (isset($this->exception->status)) {
                $this->setStatusCode($this->exception->status);
            }

            if (isset($this->exception->message)) {
                $this->setMessage($this->exception->message);
            }

            $this->failed = $this->handle();
        }
    }

    /**
     * Handles the exceptions.
     * Sets whether or not the handler failed.
     *
     * @return bool
     */
    public function handle(): bool
    {
        foreach ($this->exceptions as $exception => $case) {
            if (!is_a($this->exception, $exception)) {
                continue;
            }

            if (is_callable($case)) {
                $case($this->exception, $this);
            } else if (is_array($case)) {
                foreach ($case as $key => $value) {
                    if (is_callable([$this, $key])) {
                        call_user_func_array([$this, $key], is_array($value) ? $value : [$value]);
                    }
                }
            }
        }

        // Handle custom methods created.
        $method = Str::camel('handle_' . $this->getExceptionShortName());

        if (method_exists($this, $method)) {
            $this->$method($this->exception)->setTraced(false);
        }

        // Check for exception tracing.
        if ($this->shouldTrace()) {
            $this->mergeErrors(['trace' => $this->exception->getTrace()]);
        }

        return false;
    }

    /**
     * Handles the api response error.
     *
     * @param ApiResponseErrorException $e
     *
     * @return $this
     */
    public function handleApiResponseErrorException(ApiResponseErrorException $e)
    {
        $this
            ->mergeErrors($e->getErrors())
            ->setMessage($e->getMessage())
            ->setStatusCode($e->statusCode ?? Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this;
    }

    /**
     * Sets the HTTP status code to be used for the response.
     *
     * @param  int  $statusCode
     *
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Sets whether or not the exception is traced.
     *
     * @param  bool  $traced
     *
     * @return $this
     */
    public function setTraced(bool $traced)
    {
        $this->traced = $traced;

        return $this;
    }

    /**
     * Sets the Exception message.
     *
     * @param  string  $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Retrieves the status code for the current web response.
     *
     * @final
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Retrieves the errors for the current web response.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns the short name of the exception class.
     *
     * @return string
     */
    public function getExceptionShortName()
    {
        return (new \ReflectionClass($this->exception))->getShortName();
    }

    /**
     * Gets the exception class.
     *
     * @return string
     */
    public function getExceptionClass()
    {
        return get_class($this->exception);
    }

    /**
     * Returns whether or not the handler failed.
     *
     * @return bool
     */
    public function failed()
    {
        return $this->failed;
    }

    /**
     * Returns whether or not the exception is traced.
     *
     * @return bool
     */
    public function traced()
    {
        return $this->traced;
    }

    /**
     * Returns whether or not the exception should
     * be traced.
     *
     * @return bool
     */
    public function shouldTrace()
    {
        if (config(LARAVEL_API_RESPONSE_CONFIG . '.exceptions.stack_trace', false) && $this->traced) {
            return env('APP_DEBUG') && !isset($this->exceptions[$this->getExceptionClass()]);
        }

        return false;
    }
}
