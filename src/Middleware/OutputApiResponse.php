<?php

namespace Faridibin\LaravelApiResponse\Middleware;

use Closure;

use Faridibin\LaravelApiResponse\ApiResponse;
use Illuminate\Http\Request;

class OutputApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next)
    {
        return (new ApiResponse($request, $next))->makeResponse();
    }
}
