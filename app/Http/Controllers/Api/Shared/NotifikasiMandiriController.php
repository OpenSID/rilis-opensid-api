<?php

namespace App\Http\Controllers\Api\Shared;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\NotifikasiMandiriEntity;
use App\Http\Transformers\NotifikasiMandiriTransformer;
use App\Models\LogNotifikasiMandiri;
use Illuminate\Http\Request;

class NotifikasiMandiriController extends BaseController
{
    public function index()
    {
        $notifikasiAdmin = new NotifikasiMandiriEntity();
        return $this->fractal($notifikasiAdmin->get(), new NotifikasiMandiriTransformer(), 'notifikasi');
    }

    public function jumlah()
    {
        $user = auth('jwt')->user();
        $jumlahLog = LogNotifikasiMandiri::where('id_user_mandiri', $user->id_pend)
            ->where('read', 0)
            ->count();

        $data = [
            'jumlah' => $jumlahLog
        ];

        return $this->sendResponse($data, 'success');
    }

    public function read(Request $request)
    {
        $data = $this->validate($request, [
            'id' => 'required|integer',
        ]);

        LogNotifikasiMandiri::where('id', $data['id'])->update(['read' => 1]);

        return $this->sendResponse([], 'success');
    }

    public function show(Request $request)
    {
        $data = $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $user = auth('jwt')->user();

        $log = LogNotifikasiMandiri::where('id', $data['id'])->where('id_user_mandiri', $user->id_pend)->first();
        if ($log == null) {
            return $this->sendError([], 'Data tidak ditemukan');
        }
        return $this->sendResponse($log, 'success');
    }
}
