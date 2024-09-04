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
        $status_verifikasi = '';
        return QueryBuilder::for(LogSurat::class)
            ->select('*')
            // kades
            ->when($user->pamong != null && $user->pamong->jabatan_id == kades()->id, static function ($q) use (&$status_verifikasi) {
                $status_verifikasi = 'CASE WHEN tte = 0 THEN 2 WHEN tte = 1 THEN 1 WHEN verifikasi_kades = 1 THEN IF(tte is null,verifikasi_kades,2) ELSE 0 end ';

                return $q->selectRaw('verifikasi_kades as verifikasi')
                    ->selectRaw($status_verifikasi. ' AS status_periksa')
                    ->where('verifikasi_kades', '!=', 0)->where(function ($tte) {
                        return $tte->where('tte', '!=', 0)->orWhereNull('tte');
                    });
            })
            // sekdes
            ->when($user->pamong != null && $user->pamong->jabatan_id == sekdes()->id, static function ($q) use (&$status_verifikasi) {

                $status_verifikasi = 'CASE WHEN tte = 0 THEN 2 WHEN verifikasi_sekdes = 1 THEN IF(tte is null,IF(verifikasi_kades is null,1 , verifikasi_kades), tte) ELSE 0 end ';
                return $q->selectRaw('verifikasi_sekdes as verifikasi')
                    ->selectRaw($status_verifikasi. ' AS status_periksa')
                    ->where('verifikasi_sekdes', '!=', 0);
            })
            // selain kades dan sekdes
            ->when($user->pamong == null || !in_array($user->pamong->jabatan_id, RefJabatan::getKadesSekdes()), static function ($q) use (&$status_verifikasi) {
                $status_verifikasi = 'CASE WHEN tte = 0 THEN 2 when verifikasi_operator = 1 THEN IF(tte is null,IF(verifikasi_kades is null,IF(verifikasi_sekdes is null, 1, verifikasi_sekdes),verifikasi_kades),tte) ELSE 0 end';
                return $q->where('verifikasi_operator', '!=', 0)
                    ->selectRaw('verifikasi_operator as verifikasi')
                    ->selectRaw($status_verifikasi. ' AS status_periksa');
            })
            ->allowedFields([
                'id',
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal',
                AllowedFilter::callback('verifikasi', function (Builder $query, $value) use ($status_verifikasi) {
                    if ($value != '') {
                        return $query->whereRaw($status_verifikasi . ' = '.$value);
                    }
                    return $query;
                }),
            ])
            ->allowedSorts([
                'id',
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal',
            ])
            ->where('verifikasi_operator', '!=', '-1')
            ->whereNotNull('status')
            ->admin()
            ->jsonPaginate();
    }

    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function getAdminTolak()
    {
        $user = auth()->user()->load('pamong');
        $status_verifikasi = '';
        return QueryBuilder::for(LogSurat::class)
            ->select('*')
            ->allowedFields([
                'id',
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal'
            ])
            ->allowedSorts([
                'id',
                'no_surat',
                'nama',
                'nama_pamong',
                'tanggal',
            ])
            ->where('verifikasi_operator', '-1')
            ->whereNotNull('status')
            ->jsonPaginate();
    }
}
