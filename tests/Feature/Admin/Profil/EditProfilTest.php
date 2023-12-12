<?php

namespace Tests\Feature\Admin\Surat;

use Tests\TestCase;

class EditProfilTest extends TestCase
{
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
        $response->assertStatus(301);

        $data = [
            'nama' => 'afila',
        ];
        $response = $this->put('/api/admin/profil/update', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(301);
    }

    public function test_updatePassword()
    {
        $this->Admin_user();
        $data = [
            'lama' => '111111',
            'pass_baru' => 'afila',
            'pass_baru1' => 'afila',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

        // test gagal pass baru tidak sama dengan pass baru1
        $data = [
            'lama' => '111111',
            'pass_baru' => 'afila',
            'pass_baru1' => 'sid304',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(301);

        // test gagal pass lama salah
        $data = [
           'lama' => '123456',
           'pass_baru' => 'afila',
           'pass_baru1' => 'sid304',
        ];
        $response = $this->put('/api/admin/profil/ganti_password', $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(301);
    }
}
