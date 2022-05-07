<?php

namespace Faridibin\LaravelApiJsonResponse\Middleware;

use Closure;
use Faridibin\LaravelApiJsonResponse\Traits\HasApiResponse;
use Faridibin\LaravelApiJsonResponse\ApiResponse;
use Illuminate\Http\Request;

class OutputApiJsonResponse
{
    use HasApiResponse;

    /**
     * The recommended response to send to the client.
     *
     * @var \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private $response;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->response = new ApiResponse($request, $next);

        return $this->makeJsonResponse();
    }
}
