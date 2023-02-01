<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Penduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;

class CetakController extends Controller
{
    public function cetakBiodata()
    {
        try {
            $penduduk = auth('jwt')->user()->penduduk;
            $logo = Config::first()->url_logo;

            return Pdf::loadView('cetak.biodata', compact('penduduk', 'logo'))
                ->download("biodata_{$penduduk->nama}.pdf");
        } catch (Exception $e) {
            Log::error($e);

            return $this->fail('Tidak berhasil mengunduh', 400);
        }
    }

    public function cetakKartuKeluarga()
    {
        try {
            $anggota = auth('jwt')->user()->penduduk->keluarga->anggota->where('status_dasar', 1);
            $keluarga = auth('jwt')->user()->penduduk->keluarga;
            $kepalaKeluarga = Penduduk::with(['keluarga', 'clusterDesa'])->firstWhere('id', $keluarga->nik_kepala);

            return Pdf::loadView('cetak.salinan_kk', compact('anggota', 'kepalaKeluarga'))
                ->setPaper('A4', 'landscape')
                ->download('salinan_kk.pdf');
        } catch (Exception $e) {
            Log::error($e);

            return $this->fail('Tidak berhasil mengunduh', 400);
        }
    }
}
