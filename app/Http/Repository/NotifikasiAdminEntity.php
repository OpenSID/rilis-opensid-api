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
    public function get(String $device)
    {
        return QueryBuilder::for(LogNotifikasiAdmin::class)
        ->where('device', $device)
        ->allowedSorts([
            'id',
            'created_at'
        ])
        ->paginate();
    }
}
