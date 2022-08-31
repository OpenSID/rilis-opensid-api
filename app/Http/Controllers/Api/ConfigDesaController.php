<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\ConfigDesaTransformer;
use App\Models\Config;

class ConfigDesaController extends Controller
{
    public function index()
    {
        return $this->fractal(Config::first(), new ConfigDesaTransformer(), 'desa');
    }
}
