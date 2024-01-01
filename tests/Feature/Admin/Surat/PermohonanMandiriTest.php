<?php

namespace Tests\Feature\Admin\Surat;

use App\Models\PermohonanSurat;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PermohonanMandiriTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {

        parent::setUp();
        $this->beginDatabaseTransaction();

        PermohonanSurat::create([
            'config_id' => 1,
            'id_pemohon' => 20,
            'id_surat' => 'surat-keterangan-pindah-penduduk',
            'isian_form' => '{"url_surat":"surat-keterangan-pindah-penduduk","url_remote":"https:\/\/berputar.opendesa.id\/surat\/nomor_surat_duplikat","nik":"20","id_surat":"232","nomor":"2","telepon_pemohon":"","gunakan_format":"","jenis_permohonan":"","alasan_pindah":"","klasifikasi_pindah":"","alamat_tujuan":"","rt_tujuan":"","rw_tujuan":"","dusun_tujuan":"","desa_atau_kelurahan_tujuan":"","kecamatan_tujuan":"","kabupaten_tujuan":"","provinsi_tujuan":"","kode_pos_tujuan":"","telepon_tujuan":"","jenis_kepindahan":"","status_kk_bagi_yang_tidak_pindah":"","status_kk_bagi_yang_pindah":"","negara_tujuan":"","kode_negara":"","alamat_tujuan_(luar_negeri)":"","penanggung_jawab":"","nama_sponsor":"","tipe_sponsor":"","alamat_sponsor":"","nomor_itas_&_itap":"","tanggal_itas_&_itap":"","tanggal_pindah":"","keterangan":"","jumlah_pengikut":"","pamong_id":"","0":""}',
            'status' => 1,
            'keterangan' => 'keterangan',
            'no_hp_aktif' => '0886868787879',
            'syarat' => '{"1":"-1","2":"-1"}',
            'no_antrian' => '040823003'
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route()
    {
        $this->Admin_user();
        $response = $this->get('api/admin/surat/mandiri?filter[status]=1', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
    }

    public function test_permohonan_operator()
    {
        $this->Admin_user();
        $response = $this->get('api/admin/surat/mandiri?filter[status]=1', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                [
                    'type',
                    'id',
                    'attributes' => [
                        'nama_penduduk',
                        'nama_surat',
                        'tanggal',
                        'nik',
                        'status',
                    ]
                ]
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages'
                ]
            ],
            'links' => [
                'self',
                'first',
                'last'
            ]
        ]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        $response = $this->get('api/admin/surat/mandiri', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(4, $data);
    }

    public function test_permohonan_sekdes()
    {
        $this->Sekdes_user();
        $response = $this->get('api/admin/surat/mandiri?filter[status]=1', ['Authorization' => "Bearer $this->token"]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        $response = $this->get('api/admin/surat/mandiri', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(4, $data);
    }

    public function test_permohonan_kades()
    {
        $this->Kades_user();
        $response = $this->get('api/admin/surat/mandiri?filter[status]=1', ['Authorization' => "Bearer $this->token"]);

        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(2, $data);

        $response = $this->get('api/admin/surat/mandiri', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];
        $this->assertCount(4, $data);
    }
}
