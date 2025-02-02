<?php

namespace Faridibin\LaravelApiResponse\Http\Middleware;

use Faridibin\LaravelApiResponse\Http\ApiResponseHandler;
use Faridibin\LaravelApiResponse\Exceptions\ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Exception;

class EnsureApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->exception instanceof \Exception) {
            return $this->handleException($response->exception);
        }

        return $this->handleResponse($response);
    }

    /**
     * Handle a successful response.
     * 
     * @param  \Exception|\Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleResponse(Response $response): Response
    {
        return (new ApiResponseHandler)($response);
    }

    /**
     * Handle an exception response.
     * 
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function handleException(Exception $exception): Response
    {
        return (new ExceptionHandler)($exception);
    }
}
