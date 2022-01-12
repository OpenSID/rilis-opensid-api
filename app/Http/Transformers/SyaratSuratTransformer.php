<?php

namespace App\Http\Transformers;

use App\Models\SyaratSurat;
use League\Fractal\TransformerAbstract;

class SyaratSuratTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(SyaratSurat $surat)
    {
        return [
            'id' => $surat->ref_syarat_id,
            'nama' => $surat->ref_syarat_nama,
        ];
    }
}
