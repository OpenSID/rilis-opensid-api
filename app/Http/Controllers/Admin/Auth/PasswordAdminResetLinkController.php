<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $credentials = array_merge($request->only('email'), ['config_id' => identitas('id')]);
        $status = Password::broker('users')->sendResetLink($credentials);

        return $status == Password::RESET_LINK_SENT
            ? $this->response(__($status), 200)
            : $this->fail(__($status), 400);
    }
}
