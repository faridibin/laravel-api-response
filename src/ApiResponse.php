<?php

namespace Faridibin\LaravelApiResponse;

use Closure;

use Faridibin\LaravelApiResponse\Exceptions\ExceptionHandler;
use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ApiResponse represents an HTTP response in a specified format.
 *
 * Note that this class does not force the returned content to be an
 * object. It is however recommended that you call the makeResponse method.
 *
 * @see https://github.com/faridibin/laravel-api-response/blob/master/EXAMPLES.md
 *
 * @author Farid Adam <me@faridibin.tech>
 */

class ApiResponse extends JsonResponse
{
    use HasApiResponse;

    /**
     * The response array.
     *
     * @var array
     */
    private $response = [];

    /**
     * The exception of the response.
     *
     * @var \Exception
     */
    public $exception;

    /**
     * The authorization for the response.
     *
     * @var array
     */
    protected $authorization = null;

    /**
     * The message for the response.
     *
     * @var string
     */
    protected $message;

    /**
     * The errors for the response.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
     */
    public function __construct(Request $request, Closure $next = null)
    {
        parent::__construct();

        $this->method = $request->method();

        if (isset($next)) {
            $response = $next($request);

            parent::__construct(json_decode($response->getContent()), $response->getStatusCode(), $response->headers->all());

            $this->original = $response->getOriginalContent();

            if ($response->exception instanceof \Exception) {
                $this->exception = new ExceptionHandler($response->exception, $response->getStatusCode());

                $statusCode = $this->exception->getStatusCode();
                $message = $this->exception->getMessage();
                $errors = $this->exception->getErrors();

                $this->setStatusCode($statusCode)->setMessage($message)->setErrors($errors);
            }
        }

        $this->checkToken($request->headers->get('Authorization'));
    }

    /**
     * Makes the response based on the current data format in
     * the configuration.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function makeResponse()
    {
        $this->setResponse()
            ->setHeaders()
            ->format(config(LARAVEL_API_RESPONSE_CONFIG . '.uri_case', LARAVEL_API_RESPONSE_URI_CASE));

        switch (config(LARAVEL_API_RESPONSE_CONFIG . '.data_format', LARAVEL_API_RESPONSE_FORMAT)) {
            case 'xml':
                # TODO: Implement XML format.
                break;

            case 'yml':
                # TODO: Implement yml format
                break;

            default:
                return $this->makeJsonResponse();
        }
    }

    /**
     * Transform the JsonResponse object into an actual Response.
     * Merges headers and original content from the original response
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function makeJsonResponse()
    {
        return response()
            ->json($this->response, $this->getStatusCode(), $this->headers->all());
    }

    /**
     * Gets the status for the current api response.
     *
     * @final
     */
    public function getStatus(): string
    {
        if ($this->isSuccessful()) {
            return 'success';
        }

        return ($this->method === 'GET') ? "error" : "fail";
    }

    /**
     * Retrieves the status text.
     *
     * @final
     */
    public function getStatusText(): string
    {
        return $this->statusText;
    }

    /**
     * Set the message to be used for the response.
     *
     * @param  string  $message
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Retrieves the message.
     *
     * @final
     */
    public function getMessage(): string
    {
        return $this->message;
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
     * Retrieves the errors for the response.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the token of the request.
     *
     * @param string $token
     * @param string $scheme
     *
     * @return $this
     */
    public function setToken(string $token, string $scheme = 'Bearer')
    {
        $this->authorization = [
            'token' => $token,
            'scheme' => $scheme,
        ];

        return $this;
    }

    /**
     * Gets the response token.
     *
     * @return mixed
     */
    public function getToken(bool $scheme = false)
    {
        if ($scheme) {
            return $this->authorization['scheme'] . ' ' . $this->authorization['token'];
        }

        return $this->authorization;
    }
}
