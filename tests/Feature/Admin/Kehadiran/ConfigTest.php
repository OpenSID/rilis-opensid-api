<?php

namespace Tests\Feature\Admin\Kehadiran;

use App\Models\JamKerja;
use App\Models\SettingAplikasi;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfigTest extends TestCase
{
    private $jamKerja;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_route()
    {
        $this->Sekdes_user();

        $rentangWaktuMasuk = SettingAplikasi::where('key', 'rentang_waktu_masuk')->first()->value ?? SettingAplikasi::RENTANG_WAKTU_MASUK;
        $rentangWaktuKeluar = SettingAplikasi::where('key', 'rentang_waktu_keluar')->first()->value ?? SettingAplikasi::RENTANG_WAKTU_KELUAR;

        $response = $this->get('/api/admin/kehadiran/config', ['Authorization' => "Bearer $this->token"]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'jam_kerja',
                'rentang_waktu_masuk',
                'rentang_waktu_keluar',
            ],
            'message'
        ]);

        $response->assertJson([
            'data' => [
                'rentang_waktu_masuk' => $rentangWaktuMasuk,
                'rentang_waktu_keluar' => $rentangWaktuKeluar,
            ],
            'message' => 'success'
        ]);
    }

    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->beginDatabaseTransaction();   
        $this->settingJamAbsensi();     
    }

    private function settingJamAbsensi(){
        $now = Carbon::now();
        $namahari = $now->isoFormat('dddd');
        $this->jamKerja = JamKerja::where('nama_hari', $namahari)->first();
        $this->jamKerja->jam_masuk = $now->format('H:i');
        $this->jamKerja->jam_keluar = $now->addHour()->format('H:i');
        $this->jamKerja->save();

    }
    public function test_hadir()
    {
        $this->Sekdes_user();        

        $response = $this->json('POST', '/api/admin/kehadiran/hadir', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);
        
        $today = Carbon::now()->format('Y-m-d');
        if($this->jamKerja->status == 0){
            $response->assertStatus(401);
            $response->assertJson([                
                'message' => "Tanggal {$today} adalah Hari Libur, tidak bisa melakukan absensi"
            ]);            
            $this->markTestSkipped('Hari ini adalah hari libur');
        }        

        $response->assertStatus(200);
        // absent lagi, harusnya gagal
        $response = $this->json('POST', '/api/admin/kehadiran/hadir', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);
        $response->assertStatus(401);
        $response->assertJson([                
            'message' => "Sudah pernah melakukan absensi"
        ]);
    }

    public function test_keluar()
    {
        $this->Sekdes_user();        

        // Case berhasil setelah clock in
        $this->json('POST', '/api/admin/kehadiran/hadir', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response = $this->json('POST', '/api/admin/kehadiran/keluar', [], [
            'Accept' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ]);

        $response->assertStatus(200);
    }
}