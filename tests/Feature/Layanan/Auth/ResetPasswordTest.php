<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PendudukMandiri;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    function test_route()
    {
        $response = $this->post('api/v1/auth/reset-password');

        $response->assertStatus(200);
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
        $response = $this->post('api/v1/auth/reset-password',  $data);
        $response->assertStatus(403);

        //email salah
        $data = [
            'token' => $token,
            'email' => 'info@opendes2a.id',
            'password' => '111111',
            'password_confirmation' => '1111211'
        ];
        $response = $this->post('api/v1/auth/reset-password',  $data);
        $response->assertStatus(302);

        //konfirmasi passwor salah
        $data = [
            'token' => $token,
            'email' => 'info@opendesa.id',
            'password' => '111111',
            'password_confirmation' => '1111211'
        ];
        $response = $this->post('api/v1/auth/reset-password',  $data);
        $response->assertStatus(302);

        // benar
        $data = [
            'token' => $token,
            'email' => 'info@opendesa.id',
            'password' => '222222',
            'password_confirmation' => '222222'
        ];
        $response = $this->post('api/v1/auth/reset-password',  $data);
        $response->assertStatus(200);

        // test login
        $response = $this->post('/api/v1/auth/login', [
            'credential' => '3275014601977005',
            'password' => '222222',
        ]);

        $response->assertStatus(200);
    }
}
