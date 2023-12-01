<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Admin\BaseController as BaseController;
use App\Http\Requests\Admin\FcmRegisterTokenRequest;
use App\Models\FcmToken;
use App\Models\FcmTokenMandiri;

class FirebaseController extends BaseController
{
    public function register(FcmRegisterTokenRequest $request)
    {
        $data = $request->validated();
        $user = auth('admin')->user();

        FcmToken::updateOrCreate(
            ['device' =>  $data['device']],
            [
                'device' =>  $data['device'],
                'token' => $data['token'],
                'id_user' => $user['id']
            ]
        );

        return $this->sendResponse([], 'success');
    }

    public function register_mandiri(FcmRegisterTokenRequest $request)
    {
        $data = $request->validated();
        $user = auth('jwt')->user();


        FcmTokenMandiri::updateOrCreate(
            ['device' =>  $data['device']],
            [
                'device' =>  $data['device'],
                'token' => $data['token'],
                'id_user_mandiri' => $user['id_pend']
            ]
        );

        return $this->sendResponse([], 'success');
    }
}
