<?php

namespace App\Http\Controllers\Admin\Kehadiran;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\KehadiranEntity;
use App\Http\Transformers\LaporanKehadiranTransformer;
use App\Models\HariLibur;
use App\Models\JamKerja;
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
                'value' => [Carbon::now()->startOfYear()->format('Y-m-d'), Carbon::now()->endOfYear()->format('Y-m-d')],
            ],
        ];
        $pamong = Pamong::daftar()->get()->map(function ($pamong) {

            return [
                'name' => $pamong->pamong_nama,
                'id' => $pamong->pamong_id,
            ];
        });
        $status = Kehadiran::select('status_kehadiran')->groupBy('status_kehadiran')->get()->map(function ($status) {
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

    public function konfigurasi()
    {

        $jam_kerja = JamKerja::orderBy('id')->get();
        // $jam_kerjaP=$jam_kerja->pluck('nama_hari', 'id');
        $rentang_waktu = config('aplikasi.rentang_waktu_kehadiran');

        return $this->sendResponse(['jam_kerja' => $jam_kerja, 'rentang_waktu' => $rentang_waktu], 'success');
    }

    public function CekAbsensi()
    {
        $user = auth('admin')->user();
        $today = Carbon::now()->format('Y-m-d');
        // cek absensi
        $absensi = Kehadiran::where('pamong_id', $user->pamong_id)->where('tanggal', $today)->first();

        return $this->sendResponse($absensi, 'success');
    }

    public function cekLibur()
    {
        $libur = Carbon::now()->format('Y-m-d');
        $cekLibur = HariLibur::where('tanggal', $libur)->exists();
        return $this->sendResponse($cekLibur, 'success');
    }

    public function hadir()
    {
        Carbon::setLocale('id');
        $user = auth('admin')->user();
        if ($user->pamong_id == null) {
            return $this->sendError('Akun belum dikaitkan dengan perangkat Desa', [], 401);
        }

        // cek hari libur
        $today = Carbon::now()->format('Y-m-d');
        $cek_libur = HariLibur::where('tanggal', $today)->exists();

        if ($cek_libur) {
            return $this->sendError("Tanggal {$today} adalah Hari Libur, tidak bisa melakukan absensi", [], 401);
        }

        // cek batas absensi
        $now = Carbon::now();
        $namahari = $now->isoFormat('dddd');

        // dapatkan jam kerja hari itu

        $jamkerja = JamKerja::where('nama_hari', $namahari)->first();
        if ($jamkerja->status == 0) {
            return $this->sendError("Tanggal {$today} adalah Hari Libur, tidak bisa melakukan absensi", [], 401);
        }
        $waktuAwal = Carbon::createFromTimeString($jamkerja->jam_masuk);
        $jam_masuk = $waktuAwal->subMinutes(10);
        if ($jam_masuk->greaterThan($now)) {
            return $this->sendError("Waktu absensi dimulai jam {$jam_masuk->format('H:i:s')}", [], 401);
        }
        $waktuAwal = Carbon::createFromTimeString($jamkerja->jam_keluar);
        $jam_keluar = $waktuAwal->addMinutes(10);
        if ($jam_keluar->lessThan($now)) {
            return $this->sendError("Waktu absensi sudah melebihi batas jam {$jam_keluar->format('H:i:s')}", [], 401);
        }

        // cek absensi
        $cek_absensi = Kehadiran::where('pamong_id', $user->pamong_id)->where('tanggal', $today)->exists();
        if ($cek_absensi) {
            return $this->sendError("Sudah pernah melakukan absensi", [], 401);
        }

        Kehadiran::create([
            'pamong_id' => $user->pamong_id,
            'status_kehadiran' => 'Hadir',
            'jam_masuk' => $now->format('H:i:s'),
            'tanggal' =>  $today,
        ]);
        return response()->json(['status' => true], 200);
    }

    public function keluar()
    {
        Carbon::setLocale('id');
        $user = auth('admin')->user();
        $today = Carbon::now()->format('Y-m-d');
        // cek absensi
        $absensi = Kehadiran::where('pamong_id', $user->pamong_id)->where('tanggal', $today)->first();

        if ($absensi == null) {
            return $this->sendError("Belum melakukan absensi", [], 401);
        }
        $absensi->jam_keluar = Carbon::now()->format('H:i:s');
        $absensi->status_kehadiran = '';
        $absensi->save();
        return response()->json(['status' => true], 200);
    }
}
