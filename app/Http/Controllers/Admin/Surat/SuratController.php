<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\ArsipSuratEntity;
use App\Http\Repository\PermohonanSuratEntity;
use App\Http\Repository\SuratEntity;
use App\Http\Transformers\PermohonanMandiriTransformer;
use App\Http\Transformers\SuratAdminTransformer;
use App\Http\Transformers\SuratPermohonanTransformer;
use App\Models\Dokumen;
use App\Models\FormatSurat;
use App\Models\LogSurat;
use App\Models\LogTolak;
use App\Models\PermohonanSurat;

use App\Models\RefJabatan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratController extends BaseController
{
    public function jumlah()
    {
        $user = auth()->user()->load('pamong');

        $permohonan = LogSurat::whereNull('deleted_at')
            ->when($user->pamong && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                    return $tte->where(static function ($r) {
                        // kondisi verifikasi operator 1
                        return $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                    });
                })
                    ->when(config('aplikasi.tte') == 0, static function ($tte) {
                        return $tte->where('verifikasi_kades', '=', '0');
                    });
            })

            ->when($user->pamong && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '0');
            })->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '0');
            })->count();

        $mandiri = 0;
        $tolak = 0;
        if ($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes())) {
            $mandiri = PermohonanSurat::where('status', 1)->count();
            $tolak = LogSurat::whereNull('deleted_at')->where('verifikasi_operator', '=', '-1')->count();
        }

        $arsip = LogSurat::whereNull('deleted_at')
            ->when($user->pamong && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->where(function ($query) {
                    return $query->where('verifikasi_kades', 1)->where('tte', 1);
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_kades', 1)->whereNull('tte');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_sekdes', 1)->whereNull('verifikasi_kades');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_kades')->whereNull('verifikasi_sekdes');
                });
            })->when($user->pamong && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '1')
                    ->orWhere(function ($query) {
                        return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_sekdes');
                    });
            })
            ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
            })

            ->selectRaw("CASE WHEN log_verifikasi LIKE 'Verifikasi%' THEN 'menunggu verifikasi' WHEN log_verifikasi = 'TTE' THEN 'menunggu TTD' ELSE 'siap cetak' END as status_verifikasi")
            ->selectRaw("Count(*) as jumlah")
            ->groupBy('status_verifikasi')
            ->get()->pluck('jumlah', 'status_verifikasi');



        $data = [
            'permohonan' => $permohonan + $mandiri,
            'arsip' => [
                'siap cetak' => $arsip['siap cetak'] ?? 0,
                'menunggu verifikasi' =>  $arsip['menunggu verifikasi'] ?? 0,
                'menunggu TTD' =>  $arsip['menunggu TTD'] ?? 0,
            ],
            'tolak' => $tolak
        ];

        return $this->sendResponse($data, 'success');
    }

    public function arsip()
    {
        $arsip = new ArsipSuratEntity();
        return $this->fractal($arsip->getAdmin(), new SuratAdminTransformer(), 'arsip');
    }

    public function show(Request $request)
    {
        $id = (int) $request->id;
        $user = auth()->user()->load('pamong');
        // deklarasi jabatan
        $jabatan = $user->jabatan;

        $logSurat = LogSurat::with('formatSurat')->find($id);
        if ($logSurat == null) {
            return $this->sendError('Surat Tidak Ditemukan');
        }

        $dokumen = Dokumen::hidup()->where('id_pend', $logSurat->id_pend)->get();
        switch ($user->jabatan) {
            case 'kades':
                if (config('aplikasi.tte')) {
                    $next = 'TTE';
                } else {
                    $next = null;
                }
                break;

            case 'kades':
                $next = config('aplikasi.verifikasi_kades') ? config('aplikasi.sebutan_kepala_desa') : null;
                break;

            default:
                if (config('aplikasi.verifikasi_sekdes')) {
                    $next = config('aplikasi.sebutan_sekretaris_desa');
                } elseif (config('aplikasi.verifikasi_kades')) {
                    $next = config('aplikasi.sebutan_kepala_desa');
                } else {
                    $next = null;
                }
                break;
        }

        $surat = [
            'id' => $logSurat->id,
            'nama_pamong' => $logSurat->nama_pamong,
            'nama_jabatan' => $logSurat->nama_jabatan,
            'tanggal' => $logSurat->tanggal,
            'lampiran' => $logSurat->lampiran,
            'nik_non_warga' => $logSurat->nik_non_warga,
            'nama_non_warga' => $logSurat->nama_non_warga,
            'keterangan' => $logSurat->keterangan,
            'status' => $logSurat->status,
            'log_verifikasi' => $logSurat->log_verifikasi,
            'lampitteran' => $logSurat->tte,
            'verifikasi_operator' => $logSurat->verifikasi_operator,
            'verifikasi_kades' => $logSurat->verifikasi_kades,
            'verifikasi_sekdes' => $logSurat->verifikasi_sekdes,
            'tte' => $logSurat->tte,
            'kecamatan' => $logSurat->kecamatan,
            'jenis' => $logSurat->formatSurat->jenis,
            'penduduk' => $logSurat->penduduk,
            'pamong' => $logSurat->pamong,
            'format_surat' => $logSurat->formatSurat,
            'nomor_surat' => $logSurat->format_penomoran_surat,
        ];

        $data = [
            'surat' =>  $surat,
            'dokumen' => $dokumen,
            'next' => $next
            // 'operator' => $operator,
        ];
        return $this->sendResponse($data, 'success');
    }

    public function tolak(Request $request)
    {
        $user = auth()->user()->load('pamong');
        try {
            $id        = $request->id;
            $alasan    = $request->alasan;
            $log_surat = LogSurat::where('id', $id)->first();

            $log_surat->update([
                'verifikasi_kades'    => null,
                'verifikasi_sekdes'   => null,
                'verifikasi_operator' => -1,
            ]);

            // create log tolak
            LogTolak::create([
                'config_id'  => identitas('id'),
                'keterangan' => $alasan,
                'id_surat'   => $id,
                'created_by' => $user->id,
            ]);

            // hapus
            $file_exist = Storage::disk('ftp')->exists("desa/arsip/{$log_surat->nama_surat}");
            if ($file_exist) {
                Storage::disk('ftp')->delete("desa/arsip/{$log_surat->nama_surat}");
                $log_surat->update([
                    'nama_surat' => null
                ]);
            }

            return $this->sendResponse([], 'success');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 200);
        }

    }

    public function kembalikan(Request $request)
    {
        try {
            $id        = $request->id;
            $alasan    = $request->alasan;
            $surat   = LogSurat::find($id);
            $mandiri = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
            if ($mandiri == null) {
                return $this->sendError('Surat tidak ditemukan!', [], 200);
            }
            $mandiri->update(['status' => 0, 'alasan' => $alasan]);
            $surat->delete();

            return $this->sendResponse([], 'success');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 200);
        }
    }

    public function Setujui(Request $request)
    {
        $user = auth()->user()->load('pamong');
        $id                 = $request->id;
        $surat              = LogSurat::find($id);
        $mandiri            = PermohonanSurat::where('id_surat', $surat->id_format_surat)->where('isian_form->nomor', $surat->no_surat)->first();
        $ref_jabatan_kades  = config('aplikasi.sebutan_kepala_desa');
        $ref_jabatan_sekdes = config('aplikasi.sebutan_sekretaris_desa');

        switch ($user->pamong->jabatan_id) {
            // verifikasi kades
            case kades()->id:
                $current = 'verifikasi_kades';
                $next    = (config('aplikasi.tte') && ! in_array($surat->formatSurat->jenis, FormatSurat::RTF)) ? 'tte' : null;
                $log     = (config('aplikasi.tte')) ? 'TTE' : null;
                break;

                // verifikasi sekdes
            case sekdes()->id:
                $current = 'verifikasi_sekdes';
                $next    = config('aplikasi.verifikasi_kades') ? 'verifikasi_kades' : null;
                $log     = 'Verifikasi ' . $ref_jabatan_kades;
                break;

                // verifikasi operator
            default:
                $current = 'verifikasi_operator';
                if (config('aplikasi.verifikasi_sekdes')) {
                    $next = 'verifikasi_sekdes';
                    $log  = 'Verifikasi ' . $ref_jabatan_sekdes;
                } elseif (config('aplikasi.verifikasi_kades')) {
                    $next = 'verifikasi_kades';
                    $log  = 'Verifikasi ' . $ref_jabatan_kades;
                } else {
                    $next = null;
                    $log  = null;
                }
                break;
        }

        if ($next == null) {
            LogSurat::where('id', $id)->update([$current => 1, 'log_verifikasi' => $log]);

            if ($mandiri != null) {
                $mandiri->update(['status' => 3]);
            }
        } else {
            $log_surat = LogSurat::where('id', '=', $id)->first();
            $log_surat->update([$current => 1,  $next => 0, 'log_verifikasi' => $log]);

        }

        return $this->sendResponse([], 'success');
    }
    public function permohonan()
    {
        $surat = new SuratEntity();
        return $this->fractal($surat->permohonan(), new SuratPermohonanTransformer(), 'surat');
    }

    public function mandiri()
    {
        $mandiri = new PermohonanSuratEntity();
        return $this->fractal($mandiri->permohonanMandiri(), new PermohonanMandiriTransformer(), 'surat');
    }

    public function download(int $id)
    {
        $surat = LogSurat::find($id);
        if ($surat == null || $surat->download_surat == null) {
            return $this->sendError('File tidak ditemukan');
        }

        if (in_array($surat->formatSurat->jenis, FormatSurat::TINYMCE)) {
            return $surat->download_surat;
        }

        return $this->sendError('Download tidak suport untuk jenis surat RTF', [], 200);
    }
}
