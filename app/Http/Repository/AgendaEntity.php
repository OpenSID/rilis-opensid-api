<?php

namespace App\Http\Repository;

use App\Models\Agenda;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AgendaEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Agenda::class)
            ->allowedFields([
                'id',
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->allowedSorts([
                'id',
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(int $id)
    {
        return QueryBuilder::for(Agenda::class)
            ->allowedFields([
                'id',
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->allowedSorts([
                'id',
                'id_artikel',
                'tgl_agenda',
                'koordinator_kegiatan',
                'lokasi_kegiatan',
            ])
            ->find($id);
    }
}
