<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->response('Your email has been verified.', 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->response('A new verification link has been sent to the email address you provided.', 200);
    }
}
