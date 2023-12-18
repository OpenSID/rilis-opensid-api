<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordAdminResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'required|email',
        ]);
        $credentials = array_merge($request->only('email'), ['config_id' => identitas('id')]);
        // try {
        //     $response = Password::broker('users')->sendResetLink($credentials , function (Message $message) {
        //         $message->subject($this->getEmailSubject());
        //     });
        //     dd( $response);
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }




        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.



        $status = Password::broker('users')->sendResetLink($credentials,

        );




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





        $status = Password::broker('users')->reset(
            $data,
            function ($user) use ($request) {
                $new_password = Str::random(10);
                $user->forceFill([
                    'password' => Hash::make($new_password),
                ])->save();

            }
        );

    }
}
