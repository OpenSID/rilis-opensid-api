<?php

namespace App\Http\Repository;

use Exception;
use App\Models\Pengaduan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Storage;

class PengaduanEntity
{
    /**
     * Get resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function get()
    {
        return QueryBuilder::for(Pengaduan::whereNull('id_pengaduan'))
            ->where('nik', auth('jwt')->user()->penduduk->nik)
            ->jsonPaginate();
    }

    /**
    * Get resource data.
    *
    * @return Spatie\QueryBuilder\QueryBuilder
    */
    public function get_admin()
    {
        return QueryBuilder::for(Pengaduan::class)
            ->allowedSorts([
                'created_at',
            ])
            ->jsonPaginate();
    }

    public function show_admin(int $id)
    {
        return Pengaduan::find($id);
    }

    /**
     * Get specific resource data.
     *
     * @return Spatie\QueryBuilder\QueryBuilder
     */
    public function find(int $id)
    {
        return QueryBuilder::for(Pengaduan::where(function ($query) use ($id) {
            $query->where('id_pengaduan', $id)->orWhere('id', $id);
        }))
            ->jsonPaginate();
    }

    public function insert(Request $request)
    {
        $user = auth('jwt')->user()->penduduk;

        $file = $request->file('file');
        $fileName =  Str::random(15) . '.' . $file->getClientOriginalExtension();
        DB::beginTransaction();

        try {
            $file->storeAs(
                'desa/upload/pengaduan',
                $fileName,
                'ftp'
            );
            $pengaduan = new Pengaduan();

            $pengaduan->fill([
                'nik' => $user->nik,
                'nama' => $user->nama,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'judul' => $request->judul,
                'isi' => $request->isi,
                'foto' => $fileName,
                'ip_address' => $request->ip(),

            ])->save();
            DB::commit();
            return $pengaduan;
        } catch (Exception $e) {
            Storage::disk('ftp')->delete("desa/upload/dokumen/{$fileName}");
            DB::rollBack();

            throw $e;
        }
    }
}
