<?php

namespace App\Http\Transformers;

use App\Models\Produk;
use League\Fractal\TransformerAbstract;

class ProdukTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Produk $produk)
    {
        return [
           'id' => $produk->id,
           'nama' => $produk->nama,
           'harga' => $produk->harga,
           'potongan' => $produk->potongan,
           'hargapotongan' => round($produk->harga - (($produk->tipe_potongan == 1) ? (($produk->harga * $produk->potongan)/100) : $produk->potongan), 0),
           'dekripsi' => $produk->deskripsi,
           'foto' => $produk->url_foto,
           'telepon' => $produk->pelapak->telepon ?? '-'
        ];
    }
}
