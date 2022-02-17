<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CetakController;
use App\Http\Controllers\Api\PesanController;
use App\Http\Controllers\Api\SuratController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\BantuanController;
use App\Http\Controllers\Api\DokumenController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KomentarController;
use App\Http\Controllers\Api\PendudukController;
use App\Http\Controllers\Api\ConfigDesaController;
use App\Http\Controllers\Api\Auth\NewPinController;
use App\Http\Controllers\Api\SettingAplikasiController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Auth\AuthenticatedController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\EmailVerificationNotificationController;

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

// Admin routes
Route::prefix('admin')
    ->middleware(['auth:admin', 'can:is-admin'])
    ->group(function () {
        Route::get('penduduk', [PendudukController::class, 'index']);
    });

// Autentikasi
Route::prefix('auth')->as('jwt.')
    ->group(function () {
        Route::post('login', [AuthenticatedController::class, 'login'])->name('login');
        Route::post('logout', [AuthenticatedController::class, 'logout'])->middleware('auth:jwt')->name('logout');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['auth:jwt', 'signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware(['auth:jwt', 'throttle:6,1'])->name('verification.send');
        Route::post('change-pin', NewPinController::class)->middleware('auth:jwt')->name('change.pin');
    });

// Setting aplikasi
Route::get('setting-aplikasi/{slug}', [SettingAplikasiController::class, 'index'])->name('setting.aplikasi');
// Profil Desa
Route::get('profil-desa', [ConfigDesaController::class, 'index']);

// Artikel
Route::prefix('artikel')
    ->middleware('auth:jwt')
    ->group(function () {
        Route::get('/', [ArtikelController::class, 'index']);
        Route::get('read/{slug}', [ArtikelController::class, 'show']);
        Route::get('headline', [ArtikelController::class, 'headline']);
        Route::get('kategori', [KategoriController::class, 'index']);
        Route::get('kategori/{slug}', [KategoriController::class, 'show']);
        Route::get('komentar', [KomentarController::class, 'index']);
        Route::get('komentar/{id}', [KomentarController::class, 'show']);
    });

// Layanan mandiri
Route::prefix('layanan-mandiri')
    ->middleware('auth:jwt')
    ->group(function () {
        // Mandiri pesan masuk
        Route::prefix('pesan')
            ->group(function () {
                Route::get('tipe/{tipe}', [PesanController::class, 'index']);
                Route::get('detail/{id}', [PesanController::class, 'show']);
                Route::post('/', [PesanController::class, 'store']);
            });

        // Mandiri cetak
        Route::prefix('cetak')
            ->group(function () {
                Route::get('biodata', [CetakController::class, 'cetakBiodata']);
                Route::get('kartu-keluarga', [CetakController::class, 'cetakKartuKeluarga']);
            });

        // Mandiri program bantuan
        Route::get('bantuan', [BantuanController::class, 'index']);
        Route::get('bantuan/{id}', [BantuanController::class, 'show']);

        // Dokumen warga
        Route::prefix('dokumen')
            ->group(function () {
                Route::get('/', [DokumenController::class, 'index']);
                Route::get('{id}/download', [DokumenController::class, 'show']);
                Route::post('store', [DokumenController::class, 'store']);
                Route::delete('{id}/delete', [DokumenController::class, 'destroy']);
            });

        // Mandiri surat
        Route::prefix('surat')
            ->group(function () {
                Route::get('arsip', [SuratController::class,'arsip']);
                Route::get('permohonan', [SuratController::class,'permohonan']);
                Route::get('jenis-permohonan', [SuratController::class,'jenis']);
                Route::get('syarat', [SuratController::class,'syaratSurat']);
                Route::post('{slug}/permohonan', [SuratController::class,'store']);
                Route::post('unggah-dokumen', [SuratController::class,'unggahDokumen']);
                Route::put('{id}/permohonan', [SuratController::class,'update']);
            });
    });
