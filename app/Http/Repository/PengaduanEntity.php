<?php

namespace App\Http\Repository;

use App\Models\Pengaduan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;

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
