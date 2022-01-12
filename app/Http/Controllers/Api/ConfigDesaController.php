<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ConfigDesaTransformer;
use App\Models\Config;
use Illuminate\Support\Facades\Cache;

class ConfigDesaController extends Controller
{
    public function index()
    {
        return Cache::remember('cache_desa', 86400, function () {
            return $this->fractal(Config::first(), new ConfigDesaTransformer(), 'desa');
        });
    }
}
