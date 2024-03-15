<?php

namespace Tests\Profil;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GantiPinTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();
    }

    public function test_auth()
    {
        $response = $this->post('/api/v1/auth/change-pin', ['']);

        $response->assertStatus(500);
    }

    public function test_route()
    {
        $this->Penduduk();

        // test gagal pin
        $data = [
            'pin' => '222222',
            'password' => '121212',
            'password_confirmation' => '121212'
        ];
        $response = $this->post('/api/v1/auth/change-pin', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(403);

        // test konfirmasi pin salah
        $data = [
            'pin' => '11111',
            'password' => '121212',
            'password_confirmation' => '1221212'
        ];
        $response = $this->post('/api/v1/auth/change-pin', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        $data = [
            'pin' => '111111',
            'password' => '121212',
            'password_confirmation' => '121212'
        ];
        $response = $this->post('/api/v1/auth/change-pin', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // test pin baru
        $response = $this->post('/api/v1/auth/login', [
            'credential' => '3275014601977005',
            'password' => '121212',
        ]);
        $response->assertStatus(200);
    }
}
