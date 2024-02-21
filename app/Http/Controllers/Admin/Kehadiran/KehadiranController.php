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
        Carbon::setToStringFormat('Y-m-d');
        $ranges = [
            'Hari Ini' => [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')],
            'Kemarin' => [Carbon::yesterday()->format('Y-m-d'), Carbon::yesterday()->format('Y-m-d')],
            '7 Hari Terakhir' => [Carbon::now()->subDays(6)->format('Y-m-d'), Carbon::now()->format('Y-m-d')],
            '30 Hari Terakhir' => [Carbon::now()->subDays(29)->format('Y-m-d'), Carbon::now()->format('Y-m-d')],
            'Bulan Ini' => [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')],
            'Bulan Lalu' => [Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'), Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d')],
            'Tahun Ini' => [Carbon::now()->startOfYear()->format('Y-m-d'), Carbon::now()->endOfYear()->format('Y-m-d')],
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
