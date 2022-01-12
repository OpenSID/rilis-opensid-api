<?php

namespace App\Http\Transformers;

use App\Models\Dokumen;
use League\Fractal\TransformerAbstract;

class DokumenTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(Dokumen $dokumen)
    {
        return [
            'id' => $dokumen->id,
            'nama' => $dokumen->nama,
            'jenis_dokumen' => [
                'id' => $dokumen->jenisDokumen->ref_syarat_id ?? null,
                'nama' => $dokumen->jenisDokumen->ref_syarat_nama ?? null,
            ],
            'file' => $dokumen->urlFile,
            'tanggal_upload' => $dokumen->tgl_upload,
        ];
    }
}
