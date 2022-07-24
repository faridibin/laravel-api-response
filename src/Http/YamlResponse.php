<?php

namespace Faridibin\LaravelApiResponse\Http;

use Faridibin\LaravelApiResponse\Support\Yaml;
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
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @param array $options
     *
     * @return void
     */
    public function __construct($data = null, $status = 200, $headers = [], $options = [])
    {
        $data = new Yaml($data, $options);

        parent::__construct($data->toYaml(), $status, $headers);
    }
}
