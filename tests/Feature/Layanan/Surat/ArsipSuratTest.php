<?php

namespace Tests\Feature\Layanan\Surat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArsipSuratTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function test_daftar()
    {
        $response = $this->get('api/v1/layanan-mandiri/surat/permohonan/');

        $response->assertStatus(200);
    }
}
