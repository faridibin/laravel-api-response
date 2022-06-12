<?php

namespace Faridibin\LaravelApiJsonResponse\Middleware;

use Closure;

use Faridibin\LaravelApiJsonResponse\ApiResponse;
use Illuminate\Http\Request;

class OutputApiJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|array
     */
    public function handle(Request $request, Closure $next)
    {
        return (new ApiResponse($request, $next))->makeJsonResponse();
    }
}
