<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\DataArraySerializer;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    protected $statusCode = 200;

    public function __construct()
    {
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    public function respond($data, $callback, array $meta = [], $statusCode = 200, $headers = [])
    {
        $manager = fractal($data, $callback)
            ->serializeWith(new DataArraySerializer)
            ->addMeta($meta);

        if (\Request::has('include')) {
            $manager->parseIncludes(\Request::get('include'));
        }

        return $manager->respond($statusCode, $headers);
    }


    public function noContent()
    {
        return $this->setStatusCode(204)
            ->jsonRespond([]);
    }

    public function errorRespond($message = '操作失败', $errorCode = 400)
    {
        return $this->setStatusCode($errorCode)
            ->jsonRespond([
               'error' => $message
            ]);
    }

    public function jsonRespond(array $data, array $headers = [])
    {
        return new JsonResponse($data, $this->statusCode, $headers);
    }
}
