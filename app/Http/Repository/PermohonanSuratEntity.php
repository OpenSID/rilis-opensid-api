<?php

namespace App\Http\Repository;

use App\Models\PermohonanSurat;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PermohonanSuratEntity
{
    public function find($id)
    {
        return PermohonanSurat::where(['id' => $id, 'id_pemohon' => auth('jwt')->id()])->whereIn('status', [0, 1])->firstOrFail();
    }

    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(PermohonanSurat::class)
            ->allowedFields([
                'id',
                'status',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts([
                'id',
            ])
            ->pengguna()
            ->jsonPaginate();
    }

    /**
     * Simpan permohonan surat penduduk layanan mandiri.
     *
     * @param Request $request
     * @return PermohonanSurat
     * @throws \Throwable
     */
    public function insert(Request $request)
    {
        $permohonan = new PermohonanSurat();
        $permohonan->id_pemohon = auth('jwt')->id();
        $permohonan->id_surat = $request->slug;
        $permohonan->isian_form = json_decode($request->isian_form);
        $permohonan->status = 1;
        $permohonan->keterangan = $request->keterangan;
        $permohonan->no_hp_aktif = $request->no_hp_aktif;
        $permohonan->syarat = json_decode($request->syarat);
        $permohonan->saveOrFail();

        return $permohonan;
    }

    public function permohonanMandiri()
    {
        return QueryBuilder::for(PermohonanSurat::class)
            ->allowedFields([
                'id',
                'status',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts([
                'id',
                'created_at'
            ])
            ->jsonPaginate();
    }
}
