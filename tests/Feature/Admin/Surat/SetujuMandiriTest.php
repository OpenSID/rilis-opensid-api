<?php

namespace Tests\Feature\Admin\Surat;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetujuMandiriTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

    }

    public function test_terima()
    {
        $this->Admin_user();

        // gagal karena tidak ada id
        $response = $this->post('api/admin/surat/mandiri/setuju', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(422);

        //sukses
        $response = $this->post('api/admin/surat/mandiri/setuju', ['id' => 72, 'password' => $this->Get_password()], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }
}
