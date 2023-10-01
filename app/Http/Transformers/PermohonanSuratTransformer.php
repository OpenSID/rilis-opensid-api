<?php

namespace App\Http\Transformers;

use App\Models\PermohonanSurat;
use League\Fractal\TransformerAbstract;

class PermohonanSuratTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(PermohonanSurat $surat)
    {
        return [
            'id' => $surat->id,
            'nama_penduduk' => $surat->penduduk->nama,
            'status' => $surat->statusPermohonan,
            'tanggal_kirim' => $surat->created_at,
        ];
    }
}
