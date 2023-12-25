<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Admin\BaseController as BaseController;


class PasswordAdminResetLinkController extends BaseController
{
    /**
     * Handle an incoming password reset link request.
     *
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $credentials = array_merge($request->only('email'), ['config_id' => identitas('id')]);
        $status = Password::broker('users')->sendResetLink($credentials);

        return $status == Password::RESET_LINK_SENT
            ? $this->response(__($status), 200)
            : $this->fail(__($status), 400);
    }

    public function reset(Request $request)
    {
        $data = $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        Password::broker('users')->reset(
            $data,
            function ($user) use ($request) {
                $new_password = Str::random(10);
                $user->forceFill([
                    'password' => Hash::make($new_password),
                ])->save();

                Mail::to($request->email)->send( new ResetPassword($new_password));
                echo 'Password baru Sudah Dikirimkan. Silahkan cek email anda.';
            }
        );
    }
}
