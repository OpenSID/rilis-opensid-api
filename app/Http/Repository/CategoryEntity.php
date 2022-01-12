<?php

namespace App\Http\Repository;

use App\Models\Kategori;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Kategori::class)
            ->allowedFields([
                'id',
                'kategori',
                'slug',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                'kategori',
            ])
            ->allowedSorts([
                'id',
                'kategori',
            ])
            ->enable()
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(string $slug)
    {
        return QueryBuilder::for(Kategori::class)
            ->allowedFields([
                'id',
                'kategori',
                'slug',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                'kategori',
            ])
            ->allowedSorts([
                'id',
                'kategori',
            ])
            ->whereSlug($slug)
            ->enable()
            ->first();
    }
}
