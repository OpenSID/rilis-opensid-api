<?php

namespace App\Http\Transformers;

use App\Models\Penduduk;
use League\Fractal\TransformerAbstract;

class PendudukTransformer extends TransformerAbstract
{
    public function transform(Penduduk $penduduk)
    {
        return [
            'id' => $penduduk->id,
            'nama'=> $penduduk->nama,
            'alamat_sekarang' => $penduduk->alamat_sekarang,
            'tweb_wil_clusterdesa' => $penduduk->clusterDesa
        ];
    }
}
