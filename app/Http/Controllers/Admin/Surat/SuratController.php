<?php

namespace App\Http\Controllers\Admin\Surat;

use App\Http\Controllers\Admin\BaseController as BaseController;
use App\Http\Repository\ArsipSuratEntity;
use App\Http\Transformers\SuratAdminTransformer;
use App\Models\LogSurat;
use App\Models\RefJabatan;

class SuratController extends BaseController
{
    public function jumlah()
    {
        $user = auth()->user()->load('pamong');

        $permohonan = LogSurat::whereNull('deleted_at')
            ->when($user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                    return $tte->where(static function ($r) {
                        // kondisi verifikasi operator 1
                        return $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                    });
                });
            })
            ->when(config('aplikasi.tte') == 0, static function ($tte) {
                return $tte->where('verifikasi_kades', '=', '0');
            })
            ->when($user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '0');
            })->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '0');
            })->count();

        $arsip = LogSurat::whereNull('deleted_at')->when($user->pamong->jabatan_id == kades()->id, static function ($q) {
            return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                return $tte->where('tte', '1')->orWhere(function ($query) {
                    return $query->where('verifikasi_kades', 1)->whereNull('tte');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_sekdes', 1)->whereNull('verifikasi_kades');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_kades')->whereNull('verifikasi_sekdes');
                });
            })
                ->when(config('aplikasi.tte') == 0, static function ($tte) {
                    return $tte->where('verifikasi_kades', 1);
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_sekdes', 1)->whereNull('verifikasi_kades');
                })->orWhere(function ($query) {
                    return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_kades')->whereNull('verifikasi_sekdes');
                });
        })
            ->when($user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '=', '1')->orWhereNull('verifikasi_operator')
                    ->orWhere(function ($query) {
                        return $query->where('verifikasi_operator', 1)->whereNull('verifikasi_sekdes');
                    });
            })
            ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '=', '1')->orWhereNull('verifikasi_operator');
            })->count();
        $tolak = LogSurat::whereNull('deleted_at')->where('verifikasi_operator', '=', '-1')->count();

        $data = [
            'masuk' => $permohonan,
            'arsip' => $arsip,
            'tolak' => $tolak
        ];

        return $this->sendResponse($data, 'success');
    }

    public function arsip()
    {
        $arsip = new ArsipSuratEntity();
        return $this->fractal($arsip->getAdmin(), new SuratAdminTransformer(), 'arsip');
    }

}
