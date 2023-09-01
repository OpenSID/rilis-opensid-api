<?php

namespace Tests;

use App\Models\UserAuth;
use Illuminate\Contracts\Console\Kernel;
use Tymon\JWTAuth\Facades\JWTAuth;

trait CreatesApplication
{
    public $token;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function Admin_user()
    {
        $user = UserAuth::where('username', 'admin')->first();

        $this->token = JWTAuth::fromUser($user);

        JWTAuth::setToken($this->token);
    }

    public function Kades_user()
    {
        $user = UserAuth::where('username', 'kades')->first();

        $this->token = JWTAuth::fromUser($user);

        JWTAuth::setToken($this->token);
    }

    public function Sekdes_user()
    {
        $user = UserAuth::where('username', 'sekdes')->first();

        $this->token = JWTAuth::fromUser($user);

        JWTAuth::setToken($this->token);
    }
}
