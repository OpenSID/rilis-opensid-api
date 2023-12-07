<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VerifikasiSuratTest extends TestCase
{
    use DatabaseTransactions;

    public function test_setujui_sekdes()
    {
        $this->Sekdes_user();
        $data = [
            'id' => 8,
            'password' => '1234'
        ];

        $response = $this->json('PUT', '/api/admin/surat/setujui', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(404);
    }

    public function test_setujui_kades()
    {
        $this->Kades_user();
        $data = [
            'id' => 8,
            'password' => '1234'
        ];

        $response = $this->json('PUT', '/api/admin/surat/setujui', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(404);
    }

    public function test_tolak_sekdes()
    {
        $this->Sekdes_user();
        $data = [
            'id' => 8,
            'alasan' => 'perbaiki',
            'password' => '1234'
        ];

        $response = $this->json('PUT', '/api/admin/surat/tolak', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }

    public function test_tolak_kades()
    {
        $this->Kades_user();
        $data = [
            'id' => 8,
            'alasan' => 'perbaiki',
            'password' => '1234'
        ];

        $response = $this->json('PUT', '/api/admin/surat/tolak', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }

    public function test_kembalikan_sekdes()
    {
        $this->Sekdes_user();
        $data = [
            'id' => 8,
            'alasan' => 'perbaiki',
            'password' => '1234'
        ];

        $response = $this->json('PUT', '/api/admin/surat/kembalikan', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }

    public function test_kembalikan_kades()
    {
        $this->Kades_user();
        $data = [
            'id' => 8,
            'alasan' => 'perbaiki'
        ];

        $response = $this->json('PUT', '/api/admin/surat/kembalikan', $data, [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }

}
