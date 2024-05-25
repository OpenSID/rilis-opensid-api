<?php

namespace Tests\Feature\Layanan\Cetak;

use Tests\TestCase;

class CetakTest extends TestCase
{
    public function test_cetak_biodata()
    {
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/cetak/biodata', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }

    public function test_cetak_kk()
    {
        $this->Penduduk();
        $response = $this->get('/api/v1/layanan-mandiri/cetak/kartu-keluarga', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }
}
