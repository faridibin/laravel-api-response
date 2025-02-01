<?php

namespace Faridibin\LaravelApiResponse\Http\Middleware;

use Closure;
use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiResponse
{
    use HasApiResponse;

    /**
     * The response instance.
     *
     * @var mixed
     */
    protected mixed $response;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->response = $next($request);
        dd('here', $this->response);

        return $this->makeResponse();
    }

    /**
     * Make the response.
     */
    protected function makeResponse(): Response
    {
        //
    }
}
