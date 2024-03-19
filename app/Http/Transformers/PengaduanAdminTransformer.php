<?php

namespace App\Http\Transformers;

use App\Models\Pengaduan;
use League\Fractal\TransformerAbstract;

class PengaduanAdminTransformer extends TransformerAbstract
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
    public function transform(Pengaduan $pengaduan)
    {
        return [
            'id' => $pengaduan->id,
            'id_pengaduan' => $pengaduan->id_pengaduan,
            'email' => $pengaduan->email,
            'telepon' => $pengaduan->telepon,
            'judul' => $pengaduan->judul,
            'isi' => $pengaduan->isi,
            'status' => $pengaduan->status,
            'foto' => $pengaduan->url_foto,
            'created_at' => $pengaduan->created_at
        ];
    }
}
