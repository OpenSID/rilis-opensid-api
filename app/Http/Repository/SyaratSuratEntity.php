<?php

namespace App\Http\Repository;

use App\Models\SyaratSurat;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SyaratSuratEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(SyaratSurat::class)
            ->allowedFields([
                'ref_syarat_id',
                'ref_syarat_nama',
            ])
            ->allowedFilters([
                AllowedFilter::exact('ref_syarat_id'),
                'ref_syarat_nama',
            ])
            ->allowedSorts([
                'ref_syarat_id',
                'ref_syarat_nama',
            ])
            ->get();
    }
}
