<?php

namespace App\Http\Repository;

use App\Models\LogSurat;
use App\Models\RefJabatan;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SuratEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function permohonan()
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
                'tanggal',
                'status'
            ])
            ->allowedSorts([
                'id',
                'nama',
                'tanggal',
            ]) ->when($user->pamong && $user->pamong->jabatan_id == kades()->id, static function ($q) {
                return $q->where('verifikasi_kades', 0)->orWhere('tte', 0);
            }) ->when($user->pamong && $user->pamong->jabatan_id == sekdes()->id, static function ($q) {
                return $q->where('verifikasi_sekdes', 0);
            })->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) {
                return $q->where('verifikasi_operator', 0);
            })
            ->jsonPaginate();

    }
}
