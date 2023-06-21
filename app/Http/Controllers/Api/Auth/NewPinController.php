<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPinController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'pin' => 'required|numeric',
            'password' => 'required|numeric|confirmed|different:pin',
        ]);

        if (Hash::driver('md5')->check($request->pin, auth('jwt')->user()->pin)) {
            auth('jwt')->user()->update(['pin' => Hash::driver('md5')->make($request->password), 'ganti_pin' => 0]);

            event(new PasswordReset(auth('jwt')->user()));

            return $this->response('Pin anda berhasil dirubah.', 200);
        }

        return $this->fail('Pin lama yang anda masukkan tidak sesuai.', 403);
    }
}
