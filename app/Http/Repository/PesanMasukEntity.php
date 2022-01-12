<?php

namespace App\Http\Repository;

use App\Models\Komentar;
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
        return QueryBuilder::for(Komentar::class)
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
    public function find(int $id)
    {
        return QueryBuilder::for(Komentar::class)
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
        $comment = new Komentar();

        $comment->fill([
            'email' => $user->nik,
            'owner' => $user->nama,
            'subjek' => $request->subjek,
            'komentar' => $request->pesan,
            'tipe' => Komentar::TIPE_KELUAR,
            'status' => Komentar::NONACTIVE,
        ])->save();

        return $comment;
    }
}
