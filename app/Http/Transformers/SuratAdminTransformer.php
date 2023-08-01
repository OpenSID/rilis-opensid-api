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
        $cetak = 'Menunggu Verifikasi';

        if ($surat->status_periksa == 1) {
            $cetak = 'Siap Cetak';
        }

        if ($surat->status_periksa == 0) {
            $cetak = 'Menunggu TTD';
        }

        return [
            "id" => $surat->id,
            "nama_penduduk" => $surat->penduduk->nama?? '',
            "cetak" => $cetak,
            "nama_surat" => $surat->formatSurat->nama,
            "tanggal" => $surat->tanggal
        ];
    }
}
