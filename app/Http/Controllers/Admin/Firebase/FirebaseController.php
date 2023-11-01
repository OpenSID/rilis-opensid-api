<?php

namespace App\Http\Controllers\Admin\Firebase;

use App\Http\Controllers\Admin\BaseController as BaseController;
use App\Http\Requests\Admin\FcmRegisterTokenRequest;
use App\Models\FcmToken;

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
}
