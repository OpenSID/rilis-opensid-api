<?php

namespace Tests\Feature;

use App\Models\PendudukMandiri;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_route()
    {
        $data = [
            'token' => 'tesdfdfds',
            'email' => 'info@opendesa.id',
            'password' => '111111',
            'password_confirmation' => '111111'
        ];
        $response = $this->post('api/v1/auth/reset-password', $data);

        $response->assertStatus(403);
    }

    public function test_reset()
    {
        $user = PendudukMandiri::where('id_pend', '20')->first();
        $token = Password::createToken($user);

        //token salah
        $data = [
            'token' => 'tesdfdfds',
            'email' => 'info@opendesa.id',
            'password' => '111111',
            'password_confirmation' => '111111'
        ];
        $response = $this->post('api/v1/auth/reset-password', $data);
        $response->assertStatus(403);

        //email salah
        $data = [
            'token' => $token,
            'email' => 'info@opendes2a.id',
            'password' => '111111',
            'password_confirmation' => '1111211'
        ];
        $response = $this->post('api/v1/auth/reset-password', $data);
        $response->assertStatus(302);

        //konfirmasi passwor salah
        $data = [
            'token' => $token,
            'email' => 'info@opendesa.id',
            'password' => '111111',
            'password_confirmation' => '1111211'
        ];
        $response = $this->post('api/v1/auth/reset-password', $data);
        $response->assertStatus(302);

        // benar
        $data = [
            'token' => $token,
            'email' => 'info@opendesa.id',
            'password' => '222222',
            'password_confirmation' => '222222'
        ];
        $response = $this->post('api/v1/auth/reset-password', $data);
        $response->assertStatus(200);

        // test login
        $response = $this->post('/api/v1/auth/login', [
            'credential' => '3275014601977005',
            'password' => '222222',
        ]);

        $response->assertStatus(200);
    }
}
