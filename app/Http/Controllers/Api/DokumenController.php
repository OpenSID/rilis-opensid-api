<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\DokumenEntity;
use App\Http\Transformers\DokumenTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /** @var DokumenEntity */
    protected $dokumen;

    public function __construct(DokumenEntity $dokumen)
    {
        $this->dokumen = $dokumen;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->fractal($this->dokumen->get(), new DokumenTransformer(), 'dokumen');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_dokumen' => 'required',
            'syarat' => 'required|integer|exists:ref_syarat_surat,ref_syarat_id',
            'file' => 'required|max:10000',
        ]);

        try {
            $dokumen = $this->dokumen->insert($request);
        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 422);
        }

        return $this->fractal($dokumen, new DokumenTransformer(), 'dokumen');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dokumen = $this->dokumen->find($id);

        $this->authorize('view', $dokumen);

        return $dokumen->download_dokumen;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dokumen = $this->dokumen->find($id);

        $this->authorize('delete', $dokumen);

        try {
            Storage::disk('ftp')->delete("desa/upload/dokumen/{$dokumen->satuan}");
            $dokumen->delete();
        } catch (Exception $e) {
            Log::error($e);

            return $this->fail($e->getMessage(), $e->getCode());
        }

        return $this->response('Dokumen berhasil dihapus.', 200);
    }
}
