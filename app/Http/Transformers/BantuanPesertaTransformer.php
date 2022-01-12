<?php

namespace App\Http\Transformers;

use App\Models\BantuanPeserta;
use League\Fractal\TransformerAbstract;

class BantuanPesertaTransformer extends TransformerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function transform(BantuanPeserta $peserta)
    {
        return [
            'id' => $peserta->id,
            'no_id_kartu' => $peserta->no_id_kartu,
            'peserta' => $peserta->peserta,
            'kartu_nik' => $peserta->kartu_nik,
            'kartu_nama' => $peserta->kartu_nama,
            'kartu_tempat_lahir' => $peserta->kartu_tempat_lahir,
            'kartu_tanggal_lahir' => $peserta->kartu_tanggal_lahir,
            'kartu_alamat' => $peserta->kartu_alamat,
            'bantuan' => [
                'nama' => $peserta->bantuan->nama,
                'deskripsi' => $peserta->bantuan->ndesc,
                'tanggal_mulai' => $peserta->bantuan->sdate,
                'tanggal_berakhir' => $peserta->bantuan->edate,
            ],
        ];
    }
}
