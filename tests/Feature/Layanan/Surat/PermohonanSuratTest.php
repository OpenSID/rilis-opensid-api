<?php

namespace Tests\Feature;
namespace Tests\Feature\Layanan\Surat;

use Tests\TestCase;

class PermohonanSuratTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // test dengan surat keterangan usaha tinymce
        $slug = 'surat-keterangan-usaha';
        $this->Layanan_user();

        // test sukses
        $data = [
            'id_surat' => 192,
            'nama_usaha' => 'usaha Galon aqua',
            'syarat' => '{}',
            'keterangan' => 'keperluan usaha',
            'keperluan' => 'persyaratan pembukaan usaha',
            'no_hp_aktif' => '08523135621',
            'isian_form' =>'{"nama_usaha":"de4re","keperluan":"ere","keterangan":"ere"}',
            'url_surat' => 'surat-keterangan-usaha'
    ];
        $response = $this->post("api/v1/layanan-mandiri/surat/{$slug}/permohonan", $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);

         // test gagal

          // test sukses
        $data = [
            'id_surat' => 192,
            'nama_usaha' => 'usaha Galon aqua',
            'keperluan' => 'surat ijin',
            'no_hp_aktif' => '08523135621',
            'isian_form' =>'{"nama_usaha":"de4re","keperluan":"ere","keterangan":"ere"}',
            'url_surat' => 'surat-keterangan-usaha'
    ];
        $response = $this->post("api/v1/layanan-mandiri/surat/{$slug}/permohonan", $data, ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

    }
}
