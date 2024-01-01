<?php

namespace Tests;

use App\Models\PendudukMandiri;
use App\Models\UserAuth;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Cache;
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
        Cache::put('APP_KEY', 'base64:fTc4df0qWY59nmxJDX/ZJu4tI+JIyC7w63WP2q5FBQk=');

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

    public function Layanan_user()
    {
        $user = PendudukMandiri::where('id_pend', '2')->first();

        $this->token = JWTAuth::fromUser($user);

        JWTAuth::setToken($this->token);
    }

    public function Get_password()
    {
        return '1QNBi&4{7B0$';
    }
}
