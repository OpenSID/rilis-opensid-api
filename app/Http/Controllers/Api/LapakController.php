<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Repository\ProdukEntity;
use App\Http\Transformers\ProdukTransformer;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Transformers\ProdukDetailTransformer;

class LapakController extends BaseController
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
        

        $produk = $this->produk->find($request->id);
        
        if ($produk == null) {
            return $this->sendError('Data Tidak ditemukan');
        }

        return $this->fractal($produk, new ProdukDetailTransformer(), 'lapak');
    }
}
