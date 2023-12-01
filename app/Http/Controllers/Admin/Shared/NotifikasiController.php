<?php

namespace App\Http\Controllers\Admin\Shared;

use App\Http\Controllers\Admin\BaseController;
use App\Http\Repository\NotifikasiAdminEntity;
use App\Http\Transformers\NotifikasiAdminTransformer;
use App\Models\LogNotifikasiAdmin;
use Illuminate\Http\Request;

class NotifikasiController extends BaseController
{
    public function index(Request $request)
    {
        $notifikasiAdmin = new NotifikasiAdminEntity();
        return $this->fractal($notifikasiAdmin->get(), new NotifikasiAdminTransformer(), 'notifikasi');
    }

    public function jumlah(Request $request)
    {
        $user = auth()->user()->load('pamong');
        $jumlahLog = LogNotifikasiAdmin::where('id_user', $user->id)
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

        LogNotifikasiAdmin::where('id', $data['id'])->update(['read' => 1]);

        return $this->sendResponse([], 'success');
    }

    public function show(Request $request)
    {
        $data = $this->validate($request, [
            'id' => 'required|integer',
        ]);
        $log = LogNotifikasiAdmin::where('id', $data['id'])->first();
        return $this->sendResponse($log, 'success');
    }
}
