<?php

namespace App\Http\Controllers;

use League\Fractal\TransformerAbstract;
use Illuminate\Foundation\Bus\DispatchesJobs;
use League\Fractal\Serializer\JsonApiSerializer;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Fractal short syntax.
     *
     * @param mixed $resource
     * @return Fractal
     */
    public function fractal($resource, TransformerAbstract $transformer, string $key = 'data')
    {
        return fractal($resource, $transformer, new JsonApiSerializer())
            ->withResourceName($key)
            ->respond();
    }

    public function fail($data, int $status)
    {
        return response()->json(['code' => $status, 'messages' => $data], $status);
    }

    public function response($data, int $status)
    {
        return $this->fail($data, $status);
    }
}
