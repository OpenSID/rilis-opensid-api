<?php

namespace App\Http\Repository;

use App\Models\LogNotifikasiMandiri;
use Spatie\QueryBuilder\QueryBuilder;

class NotifikasiMandiriEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(LogNotifikasiMandiri::class)
        ->allowedSorts([
            'id',
            'created_at'
        ])
        ->paginate();
    }
}
