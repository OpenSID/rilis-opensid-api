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
            ])
            ->allowedFilters([
                'pamong_id',
                AllowedFilter::callback('range', function (Builder $query, $value) {
                    return $query->whereHas('absensi' , function ($absensi) use($value) {
                        $date = explode(' - ', $value);
                        $absensi->whereBetween('tanggal', [$date[0], $date[1]]);
                    });
                }),
                AllowedFilter::callback('status', function (Builder $query, $value) {
                    return $query->whereHas('absensi' , function ($absensi) use($value) {
                        $absensi->where('status_kehadiran', $value);
                    });
                }),
            ])
            ->get();
    }
}
