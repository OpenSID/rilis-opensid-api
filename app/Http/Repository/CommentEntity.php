<?php

namespace App\Http\Repository;

use App\Models\Komentar;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CommentEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
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
            ->enable()
            ->jsonPaginate();
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(int $id)
    {
        return QueryBuilder::for(Komentar::where('id_artikel', $id))
            ->allowedFields([
                'id',
                'id_artikel',
                'owner',
                'email',
                'subjek',
                'comment',
                'no_hp',
                'tgl_upload',
            ])
            ->jsonPaginate();
    }

    public function insert(Request $request)
    {
        $user = auth('jwt')->user()->penduduk;
        $comment = new Komentar();

        $comment->fill([
            'id_artikel' => $request->id_artikel,
            'owner' => $user->nama,
            'email' => $user->email,
            'subjek' => $request->subjek,
            'komentar' => $request->komentar,
            'status' => Komentar::NONACTIVE,
            'tipe' => Komentar::TIPE_KELUAR,
            'no_hp' => $user->telepon,
        ])->save();

        return $comment;
    }
}
