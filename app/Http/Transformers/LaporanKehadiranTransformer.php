<?php

namespace App\Http\Transformers;

use App\Models\Pamong;
use League\Fractal\TransformerAbstract;

class LaporanKehadiranTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Pamong $pamong)
    {
        return [
            'tanggal' => $pamong->tanggal,
            'id' => $pamong->id,
            'nama_pamong' =>  $pamong->gelar_depan . ' ' . $pamong->pamong_nama . '.' . $pamong->gelar_belakang,
            'jabatan' =>  $pamong->jabatan->nama ?? 'staff',
            'absensi' => $pamong->absensi,
        ];
    }
}
