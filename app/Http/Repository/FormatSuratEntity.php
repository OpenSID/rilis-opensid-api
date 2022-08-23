<?php

namespace App\Http\Repository;

use App\Models\FormatSurat;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FormatSuratEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(FormatSurat::class)
            ->allowedFields([
                'id',
                'nama',
                'kode_surat',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('kode_surat'),
                'nama',
            ])
            ->allowedSorts([
                'id',
                'nama',
                'kode_surat',
            ])
            ->mandiri()
            ->jsonPaginate();
    }

    /**
     * Get jenis surat permohonan.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function jenis()
    {
        return QueryBuilder::for(FormatSurat::class)
            ->allowedFields([
                'id',
                'nama',
                'kode_surat',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('kode_surat'),
                'nama',
            ])
            ->allowedSorts([
                'id',
                'nama',
                'kode_surat',
            ])
            ->mandiri()
            ->kunci()
            ->jsonPaginate();
    }
}
