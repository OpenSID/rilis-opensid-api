<?php

namespace Tests\Feature\Admin\Surat;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditProfilTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();

    }

    public function test_updateData()
    {
        // test berhasil
        $this->Admin_user();
        $data = [
            'email' => 'afila@gmail.com',
            'nama' => 'afila'
        ];
        $response = $this->put('/api/admin/profil/update', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // test email tidak lengkap
        $data = [
            'email' => 'afila@gm',
            'nama' => 'afila'
        ];
        $response = $this->put('/api/admin/profil/update', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // test gagal kurang field
        $data = [
            'email' => 'afila@gmail.com',
        ];
        $response = $this->put('/api/admin/profil/update', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        $data = [
            'nama' => 'afila',
        ];
        $response = $this->put('/api/admin/profil/update', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);
    }

    public function test_updatePassword()
    {
        $this->Admin_user();
        $data = [
            'lama' => $this->Get_password(),
            'pass_baru' => '356ytup0nh',
            'pass_baru1' => '356ytup0nh',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);

        // test gagal pass baru tidak sama dengan pass baru1
        $data = [
            'lama' => $this->Get_password(),
            'pass_baru' => '356ytup0nh',
            'pass_baru1' => 'sid304',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        // test gagal pass lama salah
        $data = [
           'lama' => $this->Get_password(),
           'pass_baru' => '356ytup0nh',
           'pass_baru1' => 'sid304',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);
    }
}
