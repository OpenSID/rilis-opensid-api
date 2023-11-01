<?php

namespace App\Http\Transformers;

use App\Models\LogSurat;
use League\Fractal\TransformerAbstract;

class SuratAdminTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(LogSurat $surat)
    {
        return [
            "id" => $surat->id,
            "nama_penduduk" => $surat->penduduk->nama ?? '',
            "cetak" => LogSurat::STATUS_PERIKSA[$surat->status_periksa] ?? null ,
            "nama_surat" => $surat->formatSurat->nama,
            "tanggal" => $surat->tanggal,
            "download" => url('api/admin/surat/download/'.$surat->id)
        ];
    }
}
