<?php

namespace Tests\Feature\Layanan\Desa;

use Tests\TestCase;

class ProfilDesaTest extends TestCase
{
    public function test_route()
    {
        $response = $this->get('api/v1/profil-desa');

        $response->assertStatus(200);
    }

    public function test_struktur_data()
    {
        $response = $this->get('api/v1/profil-desa');
        $response->assertJsonStructure([
            'data' => [
                'type',
                "id",
                "attributes" => [
                    "logo",
                    "email_desa",
                    "telepon",
                    "website",
                    "galeri" => [],

                    "branding",
                    "operator",
                    "perangkat_desa" => [
                        "nama_kepala_desa",
                        "nip_kepala_desa",
                        "nama_kepala_camat",
                        "nip_kepala_camat",
                    ],
                    "alamat" => [
                        "kode_pos",
                        "alamat_kantor",
                        "kantor_desa",
                        "kode_desa",
                        "kode_kecamatan",
                        "kode_kabupaten",
                        "kode_provinsi",
                        "nama_desa",
                        "nama_kecamatan",
                        "nama_kabupaten",
                        "nama_provinsi",
                        "lat",
                        "lng"
                    ],
                    "config" => [
                        "sebutan_kabupaten",
                        "sebutan_kabupaten_singkat",
                        "sebutan_kecamatan",
                        "sebutan_kecamatan_singkat",
                        "sebutan_desa",
                        "sebutan_dusun"
                    ]
                ],
            ]
        ]);

        $data = $response->decodeResponseJson()['data']['attributes'];
        $this->assertEquals("LAYANAN MANDIRI",$data['branding']);
        $this->assertEquals("Kembang Merta",$data['alamat']['nama_desa']);
    }
}
