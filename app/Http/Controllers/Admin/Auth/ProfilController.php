<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\BaseController as BaseController;
use App\Models\UserAuth;
use Illuminate\Http\Request;

class ProfilController extends BaseController
{
    public function updateprofil(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'nama' => 'required|String'
        ]);

        $user = auth()->user();

        try {
            UserAuth::where($user->id)->update(['email' => $data['email'], 'nama' => $data['nama']]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 200);
        }
    }

}
