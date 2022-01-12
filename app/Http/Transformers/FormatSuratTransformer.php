<?php

namespace App\Http\Transformers;

use App\Models\FormatSurat;
use League\Fractal\TransformerAbstract;

class FormatSuratTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormatSurat $surat)
    {
        return [
            'id' => $surat->id,
            'nama' => $surat->nama,
            'kode_surat' => $surat->kode,
        ];
    }
}
