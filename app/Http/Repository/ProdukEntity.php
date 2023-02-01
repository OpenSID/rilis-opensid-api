<?php

namespace App\Http\Repository;

use App\Models\Produk;
use Spatie\QueryBuilder\QueryBuilder;

class ProdukEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Produk::class)
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(int $id)
    {
        return QueryBuilder::for(Produk::class)
            ->find($id);
    }
}
