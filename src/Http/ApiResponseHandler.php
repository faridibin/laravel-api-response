<?php

namespace Faridibin\LaravelApiResponse\Http;

use Faridibin\LaravelApiResponse\Interfaces\HandlesResponse;
use Faridibin\LaravelApiResponse\Traits\HasApiResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseHandler implements HandlesResponse
{
    use HasApiResponse;

    /**
     * Handle a successful response.
     * 
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Response $response): Response
    {
        $content = $response->getOriginalContent();

        match (true) {
            $content instanceof Model => $this->handleModel($content),
            $content instanceof Arrayable => $this->handleArrayable($content),
            is_array($content) => $this->set($content),
            default => $this->mergeData([$content])
        };

        return response()->json(
            $this->getResponse(),
            $this->getStatusCode(),
            $this->getHeaders(),
        );
    }

    /**
     * Handle a model response.
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected function handleModel(Model $model): void
    {
        $key = Str::snake(class_basename($model));

        $this->set($key, $model->toArray());
    }

    /**
     * Handle an arrayable response.
     * 
     * @param  \Illuminate\Contracts\Support\Arrayable  $arrayable
     * @return void
     */
    protected function handleArrayable(LengthAwarePaginator|Arrayable $arrayable): void
    {
        $key = 'data';

        if (config('api-response.resource_name')) {
            $resource = class_basename($arrayable->first());

            $key = (string) Str::of($resource)->plural()->lower();
        }

        if ($arrayable instanceof LengthAwarePaginator) {
            $this->handlePaginator($arrayable, $key);
        } else {
            $this->set($key, $arrayable->toArray());
        }
    }

    /**
     * Handle a paginator response.
     * 
     * @param  \Illuminate\Pagination\LengthAwarePaginator  $paginator
     * @param  string  $key
     * @return void
     */
    protected function handlePaginator(LengthAwarePaginator $paginator, string $key): void
    {
        $data = $paginator->toArray();

        if ($key !== 'data') {
            $data[$key] = $data['data'];
            unset($data['data']);
        }

        $this->set($data);
    }
}
