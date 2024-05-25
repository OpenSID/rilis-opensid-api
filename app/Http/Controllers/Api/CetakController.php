<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Penduduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CetakController extends Controller
{
    public function cetakBiodata()
    {
        try {
            $penduduk = auth('jwt')->user()->penduduk;
            $logo = Config::first()->url_logo;

            // Generate PDF and return it as a response with status code 200
            $pdf = Pdf::loadView('cetak.biodata', compact('penduduk', 'logo'))->output();
            return response($pdf, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="biodata_' . $penduduk->nama . '.pdf"');
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

            // Generate PDF and return it as a response with status code 200
            $pdf = Pdf::loadView('cetak.salinan_kk', compact('anggota', 'kepalaKeluarga'))
                ->setPaper('A4', 'landscape')
                ->output();
            return response($pdf, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="salinan_kk.pdf"');
        } catch (Exception $e) {
            Log::error($e);

            return $this->fail('Tidak berhasil mengunduh', 400);
        }
    }
}
