<?php

namespace App\Http\Repository;

use App\Models\Dokumen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DokumenEntity
{
    public function get()
    {
        return QueryBuilder::for(Dokumen::class)
            ->allowedFields([
                'id',
                'nama',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                'nama',
            ])
            ->allowedSorts([
                'id',
                'nama',
            ])
            ->with('jenisDokumen')
            ->pengguna()
            ->jsonPaginate();
    }

    public function find($id)
    {
        return Dokumen::findOrFail($id);
    }

    /**
     * Upload dokumen penduduk.
     *
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public function insert(Request $request)
    {
        $file = $request->file('file');
        $userId = $request->user()->id_pend;
        $fileName = Str::slug($request->input('nama_dokumen')) . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $file->storeAs(
                'desa/upload/dokumen',
                $fileName,
                'ftp'
            );

            $dokumen = new Dokumen();
            $dokumen->satuan = $fileName;
            $dokumen->nama = $request->input('nama_dokumen');
            $dokumen->enabled = $dokumen::ENABLE;
            $dokumen->tgl_upload = Carbon::now();
            $dokumen->id_pend = $userId;
            $dokumen->kategori = 1;
            $dokumen->id_syarat = $request->input('syarat');
            $dokumen->dok_warga = $dokumen::DOKUMEN_WARGA;
            $dokumen->save();

            DB::commit();
        } catch (Exception $e) {
            Storage::disk('ftp')->delete("desa/upload/dokumen/{$fileName}");
            DB::rollBack();

            throw $e;
        }

        return $dokumen;
    }
}
