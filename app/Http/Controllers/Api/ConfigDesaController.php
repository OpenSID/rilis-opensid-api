<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ConfigDesaTransformer;
use App\Models\Config;

class ConfigDesaController extends Controller
{
    public function index()
    {
        $configDesa = Config::first();
        if (!$configDesa) {
            return response()->json(['code' => 404, 'message' => 'Desa dengan app_key '.get_app_key().' tidak ditemukan'], 404);
            // tidak pakai fail karena return yang diberikan adalah messages bukan message
            // return $this->fail('Desa dengan app_key '.get_app_key().' tidak ditemukan', 404);
        }
        return $this->fractal($configDesa, new ConfigDesaTransformer(), 'desa');
    }
}
