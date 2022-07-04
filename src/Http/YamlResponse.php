<?php

namespace Faridibin\LaravelApiResponse\Http;

use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class YamlResponse extends BaseResponse
{
    use Macroable, ResponseTrait {
        Macroable::__call as macroCall;
    }

    /**
     * Constructor.
     *
     * @param  mixed  $data
     * @param  int  $status
     * @param  array  $headers
     * @param  int  $options
     *
     * @return void
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        dd($data, $status, $headers, $options);
    }
}
