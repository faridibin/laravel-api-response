<?php

namespace Faridibin\LaravelApiResponse\Http;

use Faridibin\LaravelApiResponse\Support\Xml;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class XmlResponse extends BaseResponse
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
        $data = new Xml($data, $options);

        parent::__construct($data->toXml(), $status, $headers);
    }
}
