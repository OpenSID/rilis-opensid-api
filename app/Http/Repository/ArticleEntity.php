<?php

namespace App\Http\Repository;

use App\Models\Artikel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Artikel::class)
            ->allowedFields([
                'id',
                'slug',
                'judul',
                'isi',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                'judul',
                'isi',
            ])
            ->allowedSorts([
                'id',
                'judul',
                'tgl_upload',
            ])
            ->onlyArticle()
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
        return QueryBuilder::for(Artikel::class)
            ->allowedFields([
                'id',
                'slug',
                'judul',
                'isi',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                'judul',
                'isi',
            ])
            ->allowedSorts([
                'id',
                'judul',
            ])
            ->withCount('comments')
            ->whereSlug($slug)
            ->onlyArticle()
            ->enable()
            ->first();
    }

    /**
     * Get resource headline data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function headline()
    {
        return QueryBuilder::for(Artikel::class)
            ->allowedFields([
                'id',
                'slug',
                'judul',
                'isi',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('slug'),
                'judul',
                'isi',
            ])
            ->allowedSorts([
                'id',
                'judul',
            ])
            ->onlyArticle()
            ->enable()
            ->headline()
            ->jsonPaginate();
    }
}
