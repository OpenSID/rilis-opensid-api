<?php

namespace Tests\Feature\Admin\Absensi;

use Tests\TestCase;

class AbsensiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route()
    {
        $this->Admin_user();

        $response = $this->get('/api/admin/kehadiran/filter', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        $response = $this->get('/api/admin/kehadiran/data', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
    }
}
