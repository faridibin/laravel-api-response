<?php

namespace Faridibin\LaravelJsonResponse\Traits\Http\Response;

use Illuminate\Support\Str;

trait RespectSnakeCase
{
    /**
     * Sanitize response data to snake case.
     *
     * @param  \Illuminate\Support\Collection  $data
     * @return mixed
     */
    public function handleResponseData($data)
    {
        $replaced = [];

        foreach ($data as $key => $value) {
            $snakeKey = \is_array($data) ? Str::camel($key) : $key;

            $replaced[$snakeKey] = \is_array($value) ? $this->handleResponseData($value) : $value;
        }

        return $replaced;
    }
}
