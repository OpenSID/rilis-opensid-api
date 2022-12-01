<?php

namespace App\Http\Repository;

use App\Models\Penduduk;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PendudukEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Penduduk::class)
            ->allowedFields(['*'])
            ->allowedFilters([
                AllowedFilter::exact('nik'),
                AllowedFilter::exact('id'),
                'nama',
            ])
            ->allowedSorts([
                'nama',
            ])
            ->with('clusterDesa')
            ->orderBy('nama', 'ASC')
            ->jsonPaginate();
    }
}
