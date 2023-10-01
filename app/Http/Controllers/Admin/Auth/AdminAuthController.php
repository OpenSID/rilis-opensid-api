<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\BaseController as BaseController;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class AdminAuthController extends BaseController
{
    /**
     * Max attempt login throttle.
     *
     * @var int
     */
    public const MAX_ATTEMPT = 5;

    /**
     * Decay in second if failed attempt,
     * default is one hour.
     *
     * @var int
     */
    public const DECAY_SECOND = 3600;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), static::MAX_ATTEMPT)) {
            event(new Lockout($request));

            $seconds = RateLimiter::availableIn($this->throttleKey());

            return $this->sendError(__('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]), 403);
        }


        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);



        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];


        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            RateLimiter::hit($this->throttleKey(), static::DECAY_SECOND);

            return $this->fail(__('auth.failed'), 401);
        }

        return $this->sendLoginResponse($token);
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        Auth::guard($this->getGuard())->logout();

        return $this->loggedOut($request);
    }



    /**
     * Send the response after the user was authenticated.
     *
     * @return Response
     */
    protected function sendLoginResponse(string $token)
    {
        RateLimiter::clear($this->throttleKey());

        return $this->authenticated($token);
    }



    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    protected function throttleKey()
    {
        return Str::lower(request('credential')) . '|' . request()->ip();
    }

    /**
     * Get the authentication guard based name.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return request()->header('X-AUTH-GUARD') ?: 'jwt';
    }


    /**
     * {@inheritdoc}
     */
    protected function authenticated(string $token)
    {
        $user = auth('admin')->user()->load('pamong');
        $user->token = $token;
        $user->foto = base64_encode($user->foto_profil);

        return $this->sendResponse($user, 'success');
    }

    /**
     * {@inheritdoc}
     */
    protected function loggedOut(Request $request)
    {
        return $this->response('Successfully logged out', 200);
    }

    public function foto()
    {
        $user = auth('admin')->user();
        header("Accept-Ranges: bytes");
        header("Cache-Control: public");
        header('Content-Type: image/*');
        header("Content-Transfer-Encoding: binary");
        return $user->foto_profil;
    }
}
