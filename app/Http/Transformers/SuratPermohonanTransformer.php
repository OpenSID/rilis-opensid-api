<?php

namespace App\Http\Transformers;

use App\Models\LogSurat;
use League\Fractal\TransformerAbstract;

class SuratPermohonanTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(LogSurat $surat)
    {
        return [
            'id' => $surat->id,
            'nomor_surat' => $surat->no_surat,
            'nama_surat' => $surat->formatSurat->nama,
            'nama_penduduk' => $surat->penduduk->nama ?? $surat->nama_non_warga,
            'nik' => $surat->penduduk->nik ?? $surat->nik_non_warga,
            'jenis_surat' => $surat->formatSurat->nama ?? null,
            'tanggal' => $surat->tanggal,
            'tte' => $surat->tte,
        ];
    }
}
