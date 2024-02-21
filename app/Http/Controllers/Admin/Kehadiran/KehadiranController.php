<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\KehadiranEntity;
use App\Http\Transformers\LaporanKehadiranTransformer;
use App\Models\Kehadiran;
use App\Models\Pamong;
use Carbon\Carbon;

class KehadiranController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter()
    {
         $ranges = [
            [
                'label' =>  'Hari Ini',
                'value' => [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'label' => 'Kemarin',
                'value' => [Carbon::yesterday()->format('Y-m-d'), Carbon::yesterday()->format('Y-m-d')]
            ],
            [
                'label' => '7 Hari Terakhir',
                'value' => [Carbon::now()->subDays(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'label' => '30 Hari Terakhir',
                'value' => [Carbon::now()->subDays(29)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'label' => 'Bulan Ini',
                'value' => [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')]
            ],
            [
                'label' => 'Bulan Lalu',
                'value' => [Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d')]
            ],
            [
                'label' => 'Tahun Ini',
                'value' =>[Carbon::now()->startOfYear()->format('Y-m-d'), Carbon::now()->endOfYear()->format('Y-m-d')],
            ],
        ];
        $pamong = Pamong::daftar()->get();
        $status = Kehadiran::select('status_kehadiran')->groupBy('status_kehadiran')->get();
        $data = [
            'ranges' => $ranges,
            'pamong' => $pamong,
            'status' => $status,
        ];
        return $this->sendResponse($data, 'success');
    }

    public function table()
    {
        $kehadiran = new KehadiranEntity();
        return $this->fractal($kehadiran->byPamong(), new LaporanKehadiranTransformer(), 'kehadiran');
    }
}
