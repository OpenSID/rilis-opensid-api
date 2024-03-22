<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repository\PengaduanEntity;
use App\Http\Transformers\PengaduanTransformer;
use App\Libraries\Firebase;
use Exception;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    /** @var PengaduanEntity */
    protected $pengaduan;

    /**
     * Pengaduan controller constructor.
     */
    public function __construct(PengaduanEntity $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function index()
    {
        return $this->fractal($this->pengaduan->get(), new PengaduanTransformer(), 'pengaduan');
    }

    public function detail(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        return $this->fractal($this->pengaduan->find($request->id), new PengaduanTransformer(), 'pengaduan');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'telepon' => 'required',
            'judul' => 'required',
            'isi' => 'required',
            'file' => 'required|max:10000'
        ]);
        try {
            $this->pengaduan->insert($request);

            $judul      = 'Pengaduan Masyarakat - ' . $request->judul;
            $payload    = '/pengaduan/detail/' . $request->id;

            Firebase::kirim_notifikasi_admin('all', $request->isi, $judul, $payload);

        } catch (Exception $e) {
            return $this->fail($e->getMessage(), 422);
        }
        return response()->json(['status' => true, 'message' => 'success'], 200);
    }
}
