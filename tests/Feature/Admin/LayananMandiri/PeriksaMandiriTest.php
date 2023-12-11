<?php

namespace Tests\Feature\Api\Admin\LayananMandiri;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PeriksaMandiriTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_tampilkan()
    {
        $this->Admin_user();
        $response = $this->get('api/admin/surat/mandiri/periksa', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(302);

        $response = $this->get('api/admin/surat/mandiri/periksa?id=2', ['Authorization' => "Bearer $this->token"]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'config_id',
                'id_pemohon',
                'id_surat',
                'isian_form' => [
                    'url_surat',
                    'url_remote',
                    'nik',
                    'id_surat',
                    'nomor',
                ],
                'status',
                'alasan',
                'keterangan',
                'no_hp_aktif',
                'syarat',
                'created_at',
                'updated_at',
                'no_antrian',
                'syarat_surat',
                'format_surat' => [
                    'id',
                    'config_id',
                    'nama',
                    'url_surat',
                    'kode_surat',
                    'lampiran',
                    'kunci',
                    'favorit',
                    'jenis',
                    'mandiri',
                    'masa_berlaku',
                    'satuan_masa_berlaku',
                    'qr_code',
                    'logo_garuda',
                    'kecamatan',
                ]
            ]
        ]);
    }
}
