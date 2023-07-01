<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\KehadiranTransformer;
use App\Models\KehadiranPengaduan;
use App\Models\Pamong;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KehadiranController extends Controller
{
    public function index()
    {
        $kehadiran = Pamong::select(['tweb_desa_pamong.*', 'k.status_kehadiran', 'p.status'])
        ->KehadiranPamong()->daftar()->where(static function ($query) {
            $query->where('tanggal', DB::raw('curdate()'))
                ->orWhereNull('tanggal');
        })->orderBy('urut')->get();

        $perangkat = $kehadiran->each(function ($item) {
            $item->foto = Storage::disk('ftp')->url("desa/upload/user_pict/{$item->foto}");
            if ($item->id_pend != null) {
                $item->pamong_nama = $item->penduduk->nama;
                $item->foto = Storage::disk('ftp')->url("desa/upload/user_pict/{$item->penduduk->foto}");
            }
            if ($item->id_penduduk != Auth::id()) {
                return $item->id_penduduk = 0;
            }
            return $item;
        })->values()->all();

        return $this->fractal($perangkat, new KehadiranTransformer(), 'pamong');
    }

    public function lapor(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $data = [
            'waktu'       => date('Y-m-d H:i:s'),
            'status'      => 1,
            'id_penduduk' =>auth('jwt')->user()->penduduk->id,
            'id_pamong'   => (int) $request->id,
        ];

        try {
            $comment = KehadiranPengaduan::insert($data);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 400);
        }

        return $this->response('Pelaporan berhasil terkirim', 200);
    }
}
