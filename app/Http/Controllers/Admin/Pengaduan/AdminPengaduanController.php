<?php

namespace App\Http\Controllers\Admin\Pengaduan;

use Exception;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Http\Repository\PengaduanEntity;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Transformers\PengaduanTransformer;
use App\Http\Requests\Admin\StoreTanggapanPengaduanRequest;

class AdminPengaduanController extends BaseController
{
    public function index()
    {
        $pengaduan = new PengaduanEntity();
        return $this->fractal($pengaduan->get_admin(), new PengaduanTransformer(), 'pengaduan');
    }

    public function show(Request $request)
    {
        $id = $request->id;
        $pengaduan = new PengaduanEntity();

        return $this->sendResponse($pengaduan->show_admin($id), 'berhasil');
    }

    public function foto(Request $request)
    {
        $id = (int) $request->id;
        return $this->sendResponse(Pengaduan::find($id)->url_foto, 'berhasil');
    }

    public function badge(Request $request)
    {
        return $this->sendResponse(Pengaduan::whereIn('status', [1])->count(), 'berhasil');
    }

    public function tanggapi(StoreTanggapanPengaduanRequest $request)
    {
        $data = $request->validated();
        try {
            $data['ip'] = $request->ip();
            $pengaduan = new PengaduanEntity();
            $pengaduan->tanggapi($data);
            return $this->sendResponse([], 'berhasil');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 500);
        }
    }
}
