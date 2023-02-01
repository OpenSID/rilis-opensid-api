<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\ProdukEntity;
use App\Http\Transformers\ProdukTransformer;
use Illuminate\Http\Request;

class LapakController extends Controller
{
    protected $produk;

    /**
    * Article controller constructor.
    */
    public function __construct(ProdukEntity $produk)
    {
        $this->produk = $produk;
    }

    public function index()
    {
        return $this->fractal($this->produk->get(), new ProdukTransformer(), 'lapak');
    }

    public function detail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        return $this->fractal($this->produk->find($request->id), new ProdukDetailTransformer(), 'lapak');
    }
}
