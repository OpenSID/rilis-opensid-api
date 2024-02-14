<?php

namespace App\Http\Transformers;

use App\Models\Kehadiran;
use League\Fractal\TransformerAbstract;

class LaporanKehadiranTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Kehadiran $kehadiran)
    {
        $pamong =  $kehadiran->pamong;
        return [
            'tanggal' => $kehadiran->tanggal,
            'id' => $kehadiran->id,
            'nama_pamong' =>  $pamong->gelar_depan . ' ' . $kehadiran->pamong->pamong_nama .'.'. $pamong->gelar_belakang,
            'jam_masuk' => $kehadiran->jam_masuk,
            'jam_keluar' => $kehadiran->jam_keluar,
            'status_kehadiran' => $kehadiran->status_kehadiran
        ];
    }
}
