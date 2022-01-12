<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\TransformerAbstract;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

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
