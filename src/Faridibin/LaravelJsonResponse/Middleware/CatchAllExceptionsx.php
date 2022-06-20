<?php

namespace Faridibin\LaravelApiResponse\Middleware;

use Illuminate\Http\Response;

class CatchAllExceptionsx extends OutputJsonResponse
{
    /**
     * @param \Exception $e
     * @return bool
     */
    public function handleException(\Exception $e)
    {
        $json = $this->json()
            ->error(get_class($e))
            ->error($e->getMessage());

        if (env('APP_DEBUG')) {
            $json->mergeErrors(explode("\n", $e->getTraceAsString()));
        }

        $json->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
