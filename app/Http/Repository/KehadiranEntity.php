<?php

namespace App\Http\Repository;

use App\Models\Kehadiran;
use App\Models\FormatSurat;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;

class KehadiranEntity
{
    public function get()
    {
        return QueryBuilder::for(Kehadiran::with(['pamong', 'pamong.penduduk', 'pamong.jabatan']))
            ->allowedSorts([
                'id',
                'tanggal',
                'status'
            ])
            ->allowedFilters([
                'status',
                'pamong',
                AllowedFilter::callback('range', function (Builder $query, $value) {
                    $date = explode(' - ', $value);
                    return $query->whereBetween('tanggal', [$date[0], $date[1]]);
                }),
            ])
            ->jsonPaginate();
    }
}
