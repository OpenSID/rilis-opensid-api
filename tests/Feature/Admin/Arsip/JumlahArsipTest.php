<?php

namespace Tests\Feature\Admin\Arsip;

use App\Models\PermohonanSurat;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JumlahArsipTest extends TestCase
{
    // use RefreshDatabase;
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
        $response = $this->get('api/admin/surat/jumlah_arsip', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'permohonan',
                    'arsip' => [
                        'siap cetak',
                        'menunggu verifikasi',
                        'menunggu TTD',
                    ],
                    'tolak'
                ]
            ]);
    }

    public function test_jumlah_arsip_operator()
    {
        $this->Admin_user();
        $response = $this->get('api/admin/surat/jumlah_arsip', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        $this->assertEquals(3, $data['permohonan']);
        $this->assertEquals(2, $data['arsip']['siap cetak']);
        $this->assertEquals(4, $data['arsip']['menunggu verifikasi']);
        $this->assertEquals(1, $data['arsip']['menunggu TTD']);
        $this->assertEquals(1, $data['tolak']);
    }

    public function test_jumlah_arsip_sekdes()
    {
        $this->Sekdes_user();
        $response = $this->get('api/admin/surat/jumlah_arsip', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        $this->assertEquals(4, $data['permohonan']);
        $this->assertEquals(2, $data['arsip']['siap cetak']);
        $this->assertEquals(0, $data['arsip']['menunggu verifikasi']);
        $this->assertEquals(1, $data['arsip']['menunggu TTD']);
        $this->assertEquals(1, $data['tolak']);
    }

    public function test_jumlah_arsip_kades()
    {
        $this->Kades_user();
        $response = $this->get('api/admin/surat/jumlah_arsip', ['Authorization' => "Bearer $this->token"]);
        $data = $response->decodeResponseJson()['data'];

        $this->assertEquals(1, $data['permohonan']);
        $this->assertEquals(2, $data['arsip']['siap cetak']);
        $this->assertEquals(0, $data['arsip']['menunggu verifikasi']);
        $this->assertEquals(0, $data['arsip']['menunggu TTD']);
        $this->assertEquals(1, $data['tolak']);
    }
}
