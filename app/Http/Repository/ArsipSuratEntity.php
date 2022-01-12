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
}
