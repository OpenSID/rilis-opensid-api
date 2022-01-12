<?php

namespace Tests\Feature\Api\Auth;

use App\Models\PendudukMandiri;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function testSuccessLogin()
    {
        $response = $this->post('/api/v1/auth/login', [
            'credential' => '3275014601977005',
            'password' => '123456',
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'access_token' => [
                            'token',
                            'token_type',
                            'expires_in',
                        ],
                        'dasar' => [
                            'nik',
                            'nama',
                            'status_kepemilikan_ktp' => [
                                'wajib_ktp',
                                'ktp_el',
                                'status_rekam',
                                'tag_id_card',
                            ],
                            'no_kk',
                            'no_kk_sebelumnya',
                            'hubungan_dalam_keluarga',
                            'jenis_kelamin',
                            'agama',
                            'status_penduduk',
                        ],
                        'kelahiran' => [
                            'akta_kelahiran',
                            'tempat_lahir',
                            'tanggal_lahir',
                            'jenis_kelahiran',
                            'kelahiran_anak_ke',
                            'penolong_kelahiran',
                            'berat_lahir',
                            'panjang_lahir',
                        ],
                        'pendidikan_pekerjaan' => [
                            'pendidikan_kk',
                            'pendidikan_sedang_ditempuh',
                            'pekerjaan',
                        ],
                        'kewarganegaraan' => [
                            'warga_negara',
                            'no_paspor',
                            'tanggal_akhir_paspor',
                            'no_kitas',
                        ],
                        'orang_tua' => [
                            'nik_ayah',
                            'nama_ayah',
                            'nik_ibu',
                            'nama_ibu',
                        ],
                        'alamat' => [
                            'no_telepon',
                            'email',
                            'alamat',
                            'dusun',
                            'rt',
                            'rw',
                            'alamat_sebelumnya',
                        ],
                        'perkawinan' => [
                            'status',
                            'akta',
                            'tanggal',
                        ],
                        'kesehatan' => [
                            'golongan_darah',
                            'cacat',
                            'sakit_menahun',
                            'kb',
                            'status_kehamilan',
                            'asuransi',
                        ],
                    ],
                ],
            ]);
    }

    public function testFailedLoginCredential()
    {
        $response = $this->post('/api/v1/auth/login', [
            'credential' => '327501460197700',
            'password' => '12345',
        ]);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'code',
                'messages',
            ]);
    }

    public function testSuccesslogout()
    {
        $token = auth('jwt')->tokenById(PendudukMandiri::first()->id_pend);

        $response = $this->post('/api/v1/auth/logout', [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $response->assertStatus(200);
    }

    public function testFailedlogout()
    {
        $response = $this->post('/api/v1/auth/logout', [], [
            'Authorization' => "Bearer",
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(401);
    }
}
