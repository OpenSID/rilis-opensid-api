<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SettingAplikasi;
use App\Http\Transformers\PengaturanTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingAplikasiController extends Controller
{
    public function index(string $kunci)
    {
        return $this->fractal(SettingAplikasi::where('key', '=' , $kunci)->where('kategori','=','mobile')->first(), new PengaturanTransformer() , 'setting');
    }
}
