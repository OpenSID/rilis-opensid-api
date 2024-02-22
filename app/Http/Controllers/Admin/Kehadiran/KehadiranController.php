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
                'name' =>  'Hari Ini',
                'value' => [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'name' => 'Kemarin',
                'value' => [Carbon::yesterday()->format('Y-m-d'), Carbon::yesterday()->format('Y-m-d')]
            ],
            [
                'name' => '7 Hari Terakhir',
                'value' => [Carbon::now()->subDays(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'name' => '30 Hari Terakhir',
                'value' => [Carbon::now()->subDays(29)->format('Y-m-d'), Carbon::now()->format('Y-m-d')]
            ],
            [
                'name' => 'Bulan Ini',
                'value' => [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')]
            ],
            [
                'name' => 'Bulan Lalu',
                'value' => [Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d')]
            ],
            [
                'name' => 'Tahun Ini',
                'value' =>[Carbon::now()->startOfYear()->format('Y-m-d'), Carbon::now()->endOfYear()->format('Y-m-d')],
            ],
        ];
        $pamong = Pamong::daftar()->get()->map(function($pamong) {
           
            return [
                'name' => $pamong->pamong_nama,
                'id' => $pamong->pamong_id,
            ];
        });
        $status = Kehadiran::select('status_kehadiran')->groupBy('status_kehadiran')->get()->map(function($status) {
            return [
                'name' => $status->status_kehadiran,
                'value' => $status->status_kehadiran,
            ];
        });
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
