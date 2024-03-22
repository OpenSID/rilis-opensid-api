<?php

namespace App\Http\Controllers\Admin\Pengaduan;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\PengaduanEntity;
use App\Http\Transformers\PengaduanTransformer;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

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

    
}
