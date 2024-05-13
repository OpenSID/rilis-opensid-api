<?php

namespace App\Http\Repository;

use App\Models\PesanMandiri;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PesanMasukEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get(string $tipe)
    {
        return QueryBuilder::for(PesanMandiri::class)
            ->allowedFields([
                'id',
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->allowedSorts([
                'id',
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->tipePesan($tipe)
            ->pesanPengguna()
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(String $id)
    {
        return QueryBuilder::for(PesanMandiri::class)
            ->allowedFields([
                'id',
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->allowedSorts([
                'id',
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->pesanPengguna()
            ->find($id);
    }

    public function insert(Request $request)
    {
        $user = auth('jwt')->user()->penduduk;
        $comment = new PesanMandiri();

        $comment->fill([
            'penduduk_id' => $user->id,
            'owner' => $user->nama,
            'subjek' => $request->subjek,
            'komentar' => $request->pesan,
            'tipe' => PesanMandiri::MASUK,
            'status' => PesanMandiri::UNREAD,
        ])->save();

        return $comment;
    }
}
