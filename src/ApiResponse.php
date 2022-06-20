<?php

namespace Faridibin\LaravelApiResponse;

use Closure;

use Faridibin\LaravelApiResponse\Exceptions\ExceptionHandler;
use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * ApiResponse represents an HTTP response in JSON format.
 *
 * Note that this class does not force the returned JSON content to be an
 * object. It is however recommended that you do return an object as it
 * protects yourself against XSSI and JSON-JavaScript Hijacking.
 *
 * @see https://github.com/faridibin/laravel-api-json-response/blob/master/README.md
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
        $response = $next($request);

        parent::__construct(json_decode($response->getContent()), $response->getStatusCode(), $response->headers->all());

        $this->original = $response->getOriginalContent();
        $this->method = $request->method();

        if ($response->exception instanceof \Exception) {
            $this->exception = new ExceptionHandler($response->exception, $response->getStatusCode());

            $statusCode = $this->exception->getStatusCode();
            $message = $this->exception->getMessage();
            $errors = $this->exception->getErrors();

            $this->setStatusCode($statusCode);
            $this->setMessage($message);
            $this->setErrors($errors);
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
        $this->setResponse()
            ->setHeaders()
            ->format(config(LARAVEL_API_RESPONSE_CONFIG . '.uri_case', LARAVEL_API_RESPONSE_URI_CASE));

        return response()
            ->json($this->response, $this->getStatusCode(), []);
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

    // /**
    //  * The recommended response to send to the client.
    //  *
    //  * @var \Symfony\Component\HttpFoundation\Response|null
    //  */
    // public $response;

    // /**
    //  * The status code to use for the response.
    //  *
    //  * @var int
    //  */
    // public $statusCode = 200;

    // /**
    //  * The status text to use for the response.
    //  *
    //  * @var string
    //  */
    // public $statusText;

    // /**
    //  * Status codes translation table.
    //  *
    //  * @var array
    //  */
    // private $statusTexts;

    // /**
    //  * The path the client should be redirected to.
    //  *
    //  * @var string
    //  */
    // public $redirectTo;

    // /**
    //  * Converts the object into the readable array.
    //  *
    //  * @return array
    //  */
    // public function toArray()
    // {
    //     dd('Here');
    //     // $data = [
    //     //     'data' => $this->data,
    //     //     'errors' => $this->errors,
    //     //     'success' => $this->isSuccess(),
    //     //     'status_code' => $this->statusCode
    //     // ];

    //     // if (!is_null($this->token)) {
    //     //     $data['token'] = $this->token;
    //     // }

    //     // return $data;
    // }

    // public function __toString(): string
    // {
    //     dd('Here');
    // }



    // public function data(array $data)
    // {
    //     $this->data = $data;

    //     return $this;
    // }

    // /**
    //  * Set the HTTP status code to be used for the response.
    //  *
    //  * @param  int  $statusCode
    //  * @return $this
    //  */
    // public function status(int $statusCode)
    // {
    //     // $this->statusCode = $statusCode;
    //     // $this->statusText = $this->statusTexts[$statusCode] ?? 'unknown status';

    //     // if() {

    //     // }else {

    //     // }

    //     // if($this->statusCode >= 200 && $this->statusCode < 300) {
    //     //     $this->status = 'success';
    //     //     $this->success = true;
    //     // } else {
    //     //     $this->status = ('' === 'GET') ? "error": "fail";
    //     //     $this->success = false;
    //     // }

    //     // return $this;
    // }



    // // /**
    // //  * Get all of the validation error messages.
    // //  *
    // //  * @return array
    // //  */
    // // public function errors()
    // // {
    // //     return $this->validator->errors()->messages();
    // // }

    // // /**
    // //  * Set the error bag on the exception.
    // //  *
    // //  * @param  string  $errorBag
    // //  * @return $this
    // //  */
    // // public function errorBag($errorBag)
    // // {
    // //     $this->errorBag = $errorBag;

    // //     return $this;
    // // }

    // /**
    //  * Set the exception of the response.
    //  *
    //  * @param \Exception $exception
    //  * @return $this
    //  */
    // public function exception(\Exception|null $exception) {
    //     $this->exception = '';
    // }

    // // /**
    // //  * Set the URL to redirect to on a validation error.
    // //  *
    // //  * @param  string  $url
    // //  * @return $this
    // //  */
    // // public function redirectTo($url)
    // // {
    // //     $this->redirectTo = $url;

    // //     return $this;
    // // }

    // // /**
    // //  * Get the underlying response instance.
    // //  *
    // //  * @return \Symfony\Component\HttpFoundation\Response|null
    // //  */
    // // public function getResponse()
    // // {
    // //     return $this->response;
    // // }
}
