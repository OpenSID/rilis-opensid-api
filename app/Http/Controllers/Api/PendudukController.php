<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\PendudukEntity;
use App\Http\Transformers\PendudukTransformer;
use App\Models\Penduduk;

class PendudukController extends Controller
{
    /** @var PendudukEntity */
    protected $penduduk;

    /**
     * Penduduk controller constructor.
     */
    public function __construct(PendudukEntity $penduduk)
    {
        $this->penduduk = $penduduk;
    }

    public function index()
    {
        return $this->fractal($this->penduduk->get(), new PendudukTransformer(), 'penduduk');
    }
}
