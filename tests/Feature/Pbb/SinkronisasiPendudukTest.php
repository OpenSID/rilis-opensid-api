<?php

namespace Tests\Feature\Admin\Absensi;

use Tests\TestCase;

class SinkronisasiPendudukTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route()
    {
        $this->Admin_user();

        $response = $this->get('/api/v1/admin/penduduk', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }
}
