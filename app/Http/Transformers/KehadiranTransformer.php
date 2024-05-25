<?php

namespace App\Http\Transformers;

use App\Models\Pamong;
use League\Fractal\TransformerAbstract;

class KehadiranTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Pamong $kehadiran)
    {

        return [
            'id' => $kehadiran->pamong_id,
            'nama' => $kehadiran->pamong_nama,
            'sex' => $kehadiran->pamong_sex ?? ($kehadiran->penduduk->sex ?? null),
            'foto' => $kehadiran->foto,
            'jabatan' => $kehadiran->jabatan->nama,
            'status_kehadiran' => $kehadiran->status_kehadiran,
            'status' => $kehadiran->status
        ];
    }
}
