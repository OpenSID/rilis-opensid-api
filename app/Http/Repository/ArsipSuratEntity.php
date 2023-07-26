<?php

namespace App\Http\Repository;

use App\Models\LogSurat;
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
                'verifikasi'
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
            // ->selectRaw('k.url_surat, k.jenis')
            ->whereNotNull('status')
            ->admin()
            ->jsonPaginate();
    }
}
