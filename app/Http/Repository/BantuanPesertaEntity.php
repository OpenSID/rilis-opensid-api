<?php

namespace App\Http\Repository;

use App\Models\BantuanPeserta;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BantuanPesertaEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(BantuanPeserta::class)
            ->allowedFields([
                'id',
                'nama',
                'ndesc',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'nama',
                'ndesc',
            ])
            ->allowedSorts([
                'id',
                'nama',
                'ndesc',
            ])
            ->peserta()
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(int $id)
    {
        return QueryBuilder::for(BantuanPeserta::class)
            ->allowedFields([
                'id',
                'nama',
                'ndesc',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'nama',
                'ndesc',
            ])
            ->allowedSorts([
                'id',
                'nama',
                'ndesc',
            ])
            ->peserta()
            ->find($id);
    }
}
