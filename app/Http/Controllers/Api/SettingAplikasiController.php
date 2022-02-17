<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\PengaturanTransformer;
use App\Models\SettingAplikasi;

class SettingAplikasiController extends Controller
{
    public function index(string $kunci)
    {
        return $this->fractal(SettingAplikasi::where('key', '=', $kunci)->where('kategori', '=', 'mobile')->first(), new PengaturanTransformer(), 'setting');
    }
}
