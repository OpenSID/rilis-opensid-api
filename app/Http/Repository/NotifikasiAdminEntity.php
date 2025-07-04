<?php

namespace App\Http\Repository;

use App\Models\LogNotifikasiAdmin;
use Spatie\QueryBuilder\QueryBuilder;

class NotifikasiAdminEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(LogNotifikasiAdmin::class)
        ->allowedSorts([
            'id',
            'created_at'
        ])
        ->where('id_user', auth()->user()->id)
        ->paginate();
    }
}
