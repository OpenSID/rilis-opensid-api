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
        $device = $request->header('Device');
        $notifikasiAdmin = new NotifikasiAdminEntity();
        return $this->fractal($notifikasiAdmin->get($device), new NotifikasiAdminTransformer(), 'notifikasi');

    }

    public function jumlah(Request $request)
    {
        $device = $request->header('Device');
        $user = auth()->user()->load('pamong');
        $jumlahLog = LogNotifikasiAdmin::where('device', $device)
            ->where('id_user', $user->id)
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

        $device = $request->header('Device');
        LogNotifikasiAdmin::where('device', $device)->where('id', $data['id'])->where('device', $device)->update(['read' => 1]);

        return $this->sendResponse([], 'success');
    }

    public function show(Request $request)
    {
        $data = $this->validate($request, [
            'id' => 'required|integer',
        ]);

        $device = $request->header('Device');

        $log = LogNotifikasiAdmin::where('device', $device)->where('id', $data['id'])->where('device', $device)->first();
        return $this->sendResponse($log, 'success');
    }
}
