<?php

namespace Faridibin\LaravelApiResponse\Exceptions;

use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Faridibin\LaravelApiResponse\Interfaces\HandlesResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class ExceptionHandler implements HandlesResponse
{
    use HasApiResponse;

    /**
     * Handle an exception response.
     * 
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Exception $exception): Response
    {
        $exceptions = config('api-response.exceptions');

        if ($exception->getPrevious()) {
            $exception = $exception->getPrevious();
        }

        if (isset($exception->status)) {
            $this->statusCode = $exception->status;
        }

        if (isset($exceptions[get_class($exception)])) {
            $handlers = $exceptions[get_class($exception)];

            if (is_array($handlers)) {
                foreach ($handlers as $handler => $params) {
                    match (true) {
                        is_callable([$this, $handler]) => $this->$handler($params),
                        is_callable($handler) => $handler($params, $this),
                        default => null,
                    };
                }
            }

            if (is_callable($handlers)) {
                $handlers($exception, $this);
            }
        }

        if ($this->shouldTrace()) {
            $this->mergeErrors(['trace' => $exception->getTrace()]);
        }

        return response()->json(
            $this->getResponse(),
            $this->getStatusCode(),
            $this->getHeaders(),
        );
    }

    /**
     * Set response message for the ModelNotFoundException.
     * @param string $message
     * @return $this
     */
    public function setModelNotFoundMessage(string $message): static
    {
        // TODO: Implement setModelNotFoundMessage() method. Make it dynamic.
        $this->message = $message;

        return $this;
    }

    /**
     * Determine if the response should trace the data.
     * @return bool
     */
    protected function shouldTrace(): bool
    {
        return config('api-response.trace', app()->environment('local'));
    }
}
