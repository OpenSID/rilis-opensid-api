<?php

namespace App\Services\Surat\Layanan;

use Illuminate\Support\Facades\DB;

class SuratBioPenduduk extends SuratAbstract
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return $this->validate(
            $this->request,
            $this->defaultRules([
                'keterangan' => 'required|string',
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function form()
    {
        return $this->defaultForm([
            ['type' => 'textarea', 'required' => false, 'label' => 'Keterangan', 'name' => 'keterangan', 'subtype' => 'textarea'],
            [
                'type' => 'table',
                'required' => false,
                'label' => 'Anggota Keluarga',
                'name' => 'anggota_keluarga',
                'values' => $this->listAnggotaKeluarga(),
            ],
        ]);
    }

    protected function listAnggotaKeluarga()
    {
        return DB::table('tweb_penduduk as u')
            ->selectRaw("
                u.id,
                u.nik,
                u.nama,
                DATE_FORMAT(
                FROM_DAYS(
                    TO_DAYS(NOW())- TO_DAYS(`tanggallahir`)
                ),
                '%Y'
                )+ 0 AS umur,
                x.nama AS sex,
                h.nama AS hubungan
            ")
            ->leftJoin('tweb_penduduk_sex as x', 'u.sex', '=', 'x.id')
            ->leftJoin('tweb_penduduk_hubungan as h', 'u.kk_level', '=', 'h.id')
            ->leftJoin('tweb_keluarga as k', 'u.id_kk', '=', 'k.id')
            ->where('u.status_dasar', 1)
            ->where('u.id_kk', auth('jwt')->user()->penduduk->id_kk)
            ->get();
    }
}
