<?php

namespace App\Http\Transformers;

;

use App\Models\PermohonanSurat;
use League\Fractal\TransformerAbstract;

class PermohonanMandiriTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(PermohonanSurat $surat)
    {

        return [
            'id' => $surat->id,
            'nama_penduduk' => $surat->penduduk->nama,
            'nama_surat' => $surat->formatSurat->nama,
            'nik' => $surat->penduduk->nik,
            'status' => $surat->statusPermohonan,
            'tanggal' => $surat->created_at,
            'tinymce' => in_array($surat->formatSurat->jenis, $surat->formatSurat::TINYMCE),
        ];
    }
}
