<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\PasswordAdminResetLinkController;
use App\Http\Controllers\Admin\Auth\ProfilController;
use App\Http\Controllers\Admin\Kehadiran\KehadiranController;
use App\Http\Controllers\Admin\Pengaduan\AdminPengaduanController;
use App\Http\Controllers\Admin\Shared\NotifikasiController;
use App\Http\Controllers\Admin\Statistik\StatistikController;
use App\Http\Controllers\Admin\Surat\LayananMandiriController;
use App\Http\Controllers\Admin\Surat\SuratController;
use App\Http\Controllers\Admin\Surat\TteController;
use App\Http\Controllers\Api\ConfigDesaController;
use App\Http\Controllers\Firebase\FirebaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Profil Desa
Route::get('profil-desa', [ConfigDesaController::class, 'index']);

Route::post('login', [AdminAuthController::class, 'login'])->name('adminLogin');
Route::post('resetpassword', [PasswordAdminResetLinkController::class, 'store'])->name('resetAdminPassword');
Route::get('reset', [PasswordAdminResetLinkController::class, 'reset'])->name('resetpassword');
Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('adminLogout');
Route::get('/validate-token', function () {
    return ['data' => 'Token is valid', 'success' => true];
})->middleware('auth:admin');
Route::group(['prefix' => 'notifikasi', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('indexNotifikasi');
    Route::post('/read', [NotifikasiController::class, 'read'])->name('readNotifikasi');
    Route::get('/jumlah', [NotifikasiController::class, 'jumlah'])->name('jumlahNotifikasi');
    Route::get('/show', [NotifikasiController::class, 'show'])->name('showNotifikasi');
});
Route::group(['prefix' => 'fcm'], function () {
    Route::post('/register', [FirebaseController::class, 'register'])->name('arsip');
});

Route::group(['prefix' => 'statistik'], function () {
    Route::get('/penduduk', [StatistikController::class, 'kependudukan']);
});

Route::group(['prefix' => 'surat', 'middleware' => ['auth:admin']], function () {
    Route::get('/jumlah_arsip', [SuratController::class, 'jumlah'])->name('jumlahArsip');
    Route::get('/arsip', [SuratController::class, 'arsip'])->name('arsip');
    Route::get('/arsipditolak', [SuratController::class, 'arsiptolak'])->name('arsiptolak');
    Route::put('/setujui', [SuratController::class, 'setujui'])->name('arsipsetujui');
    Route::put('/tolak', [SuratController::class, 'tolak'])->name('arsip');
    Route::put('/kembalikan', [SuratController::class, 'kembalikan'])->name('arsipkembalikan');
    Route::get('/show', [SuratController::class, 'show'])->name('show');
    Route::get('/permohonan', [SuratController::class, 'permohonan'])->name('show');
    Route::get('/mandiri', [LayananMandiriController::class, 'index'])->name('show');
    Route::get('/mandiri/periksa', [LayananMandiriController::class, 'show'])->name('show');
    Route::post('/mandiri/setuju', [LayananMandiriController::class, 'setuju'])->name('show');
    Route::get('/mandiri/download_dokumen', [LayananMandiriController::class, 'downloadDokumen'])->name('show');
    Route::put('/mandiri/tolak', [LayananMandiriController::class, 'tolak'])->name('tolakmandiri');
    Route::post('/download/{id}', [SuratController::class, 'download'])->name('arsipdownload')->where('id', '[0-9]+');
    Route::post('/tte/{id}', [TteController::class, 'update'])->name('tandaTanganiSurat');
});
Route::group(['prefix' => 'profil', 'middleware' => ['auth:admin']], function () {
    Route::get('/foto', [AdminAuthController::class, 'foto'])->name('fotoprofil');
    Route::put('/update', [ProfilController::class, 'updateprofil'])->name('updateprofil');
    Route::put('/ganti_password', [ProfilController::class, 'updatepassword'])->name('gantipassword');
});

Route::group(['prefix' => 'kehadiran', 'middleware' => ['auth:admin']], function () {
    Route::get('/data', [KehadiranController::class, 'table'])->name('tabelkehadiran');
    Route::get('/filter', [KehadiranController::class, 'filter'])->name('filterkehadiran');
    Route::get('/config', [KehadiranController::class, 'konfigurasi'])->name('konfigurasikehadiran');
    Route::get('/cek_libur', [KehadiranController::class, 'cekLibur'])->name('cekLiburkehadiran');
    Route::get('/cek_hadir', [KehadiranController::class, 'CekAbsensi'])->name('CekAbsensikehadiran');
    Route::post('/hadir', [KehadiranController::class, 'hadir'])->name('hadirkehadiran');
    Route::post('/keluar', [KehadiranController::class, 'keluar'])->name('keluarkehadiran');
});

Route::group(['prefix' => 'pengaduan', 'middleware' => ['auth:admin']], function () {
    Route::get('/', [AdminPengaduanController::class, 'index'])->name('indexpengaduanadmin');
    Route::get('/show', [AdminPengaduanController::class, 'show'])->name('showpengaduanadmin');
    Route::get('/foto', [AdminPengaduanController::class, 'foto'])->name('fotopengaduanadmin');
    Route::get('/badge', [AdminPengaduanController::class, 'badge'])->name('badgepengaduanadmin');
    Route::post('/tanggapi', [AdminPengaduanController::class, 'tanggapi'])->name('tanggapipengaduanadmin');
});
