<?php

namespace App\Http\Repository;

use App\Models\Kehadiran;
use App\Models\Pamong;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function byPamong()
    {
        return QueryBuilder::for(Pamong::with('absensi')->daftar())
            ->allowedSorts([
                'id',
                'tanggal',
                'status'
            ])
            ->allowedFilters([
                'status',
                'pamong',
                AllowedFilter::callback('range', function (Builder $query, $value) {
                    // return $query->whereHas('kehadiran' , function ($kehadiran) use($value) {
                    //     // $date = explode(' - ', $value);
                    //     // $kehadiran->whereBetween('tanggal', [$date[0], $date[1]]);
                    // });
                }),
            ])
            ->get();
    }
}
