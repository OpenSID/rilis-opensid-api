<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Traits\LoginRequestTrait;
use App\Http\Transformers\PendudukMandiriTransformer;
use App\Http\Transformers\UserTransformer;
use Illuminate\Http\Request;

class AuthenticatedController extends Controller
{
    /**
     * This trait handles authenticating users for the application.
     */
    use LoginRequestTrait;

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
     * {@inheritdoc}
     */
    protected function authenticated(string $token)
    {
        $user = auth($this->getGuard())->user();
        $user->token = $token;

        return $this->getGuard() == 'admin'
            ? $this->fractal($user, new UserTransformer(), 'user')
            : $this->fractal($user, new PendudukMandiriTransformer(), 'penduduk');
    }

    /**
     * {@inheritdoc}
     */
    protected function loggedOut(Request $request)
    {
        return $this->response('Successfully logged out', 200);
    }
}
