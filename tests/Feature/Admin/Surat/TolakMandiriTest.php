<?php

namespace Tests\Feature\Admin\Surat;

use Tests\TestCase;

class TolakMandiriTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_tolak()
    {
        // test berhasil
        $this->Admin_user();
        $response = $this->put('/api/admin/surat/mandiri/tolak', ['id' => 72], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);

        // test tolak gagal karena id tidak ada
        $this->Admin_user();
        $response = $this->put('/api/admin/surat/mandiri/tolak', ['id' => 10], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(404);

        // test tolak gagal karena id masih dalam proses
        $this->Admin_user();
        $response = $this->put('/api/admin/surat/mandiri/tolak', ['id' => 1], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(404);

        // test tolak gagal id bukan integer
        $this->Admin_user();
        $response = $this->put('/api/admin/surat/mandiri/tolak', ['id' => 'abc'], ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(302);
    }
}
