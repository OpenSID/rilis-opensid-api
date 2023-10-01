<?php

namespace App\Http\Repository;

use App\Models\LogSurat;
use App\Models\RefJabatan;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArsipSuratEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(LogSurat::class)
            ->allowedFields([
                'id',
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
            ])
            ->allowedSorts([
                'id',
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
            ])
            ->pengguna()
            ->jsonPaginate();
    }

    public function find($id)
    {
        return LogSurat::findOrFail($id);
    }

    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function getAdmin()
    {
        $user = auth()->user()->load('pamong');
        return QueryBuilder::for(LogSurat::class)
            ->allowedFields([
                'id',
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
                AllowedFilter::callback('verifikasi', function (Builder $query, $value) use ($user) {
                    // kades
                    $query->when($user->pamong->jabatan_id == kades()->id, static function ($q) use ($value) {
                        return $q->where('verifikasi_kades', $value)->whereOr('tte', $value);
                    })
                    // sekdes
                    ->when($user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                        return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                            return $tte->where(static function ($r) {
                                // kondisi verifikasi operator 1
                                return $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                            });
                        });
                    })
                    // selain kades dan sekdes
                    ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                        return $q->when(config('aplikasi.tte') == 1, static function ($tte) {
                            return $tte->where(static function ($r) {
                                // kondisi verifikasi operator 1
                                return $r->where('verifikasi_kades', '=', 0)->orWhere('tte', '=', 0);
                            });
                        });
                    })

                    ;
                }),
            ])
            ->allowedSorts([
                'id',
                'no_surat',
                'nama',
                'pamong_nama',
                'tanggal',
            ])
            ->select('*')
            ->selectRaw('nama_pamong as pamong_nama')

            // kades
            ->when($user->pamong != null && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->where('verifikasi_kades', '!=', 0)->where(function ($tte) {
                    return $tte->where('tte', '!=', 0)->orWhereNull('tte');
                });
            })
            // sekdes
            ->when($user->pamong != null && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', '!=', 0);
            })
            // selain kades dan sekdes
            ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', '!=', 0);
            })
            ->where('verifikasi_operator', '!=', '-1')
            ->whereNotNull('status')
            ->admin()
            ->jsonPaginate();
    }
}
