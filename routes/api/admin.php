<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Surat\SuratController;
use App\Http\Controllers\Admin\Surat\TteController;
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

Route::post('login', [AdminAuthController::class, 'login'])->name('adminLogin');
Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('adminLogout');
Route::get('/validate-token', function () {
    return ['data' => 'Token is valid', 'success' => true];
})->middleware('auth:admin');
Route::group(['prefix' => 'surat', 'middleware' => ['auth:admin']], function () {
    Route::get('/jumlah_arsip', [SuratController::class, 'jumlah'])->name('jumlahArsip');
    Route::get('/arsip', [SuratController::class, 'arsip'])->name('arsip');
    Route::put('/setujui', [SuratController::class, 'setujui'])->name('arsipsetujui');
    Route::put('/tolak', [SuratController::class, 'tolak'])->name('arsip');
    Route::put('/kembalikan', [SuratController::class, 'kembalikan'])->name('arsipkembalikan');
    Route::get('/show', [SuratController::class, 'show'])->name('show');
    Route::get('/permohonan', [SuratController::class, 'permohonan'])->name('show');
    Route::get('/mandiri', [SuratController::class, 'mandiri'])->name('show');
    Route::get('/download/{id}', [SuratController::class, 'download'])->name('arsipdownload')->where('id', '[0-9]+');
    Route::post('/tte/{id}', [TteController::class, 'update'])->name('tandaTanganiSurat');
});
Route::group(['prefix' => 'profil', 'middleware' => ['auth:admin']], function () {
    Route::get('/foto', [AdminAuthController::class, 'foto'])->name('fotoprofil');
});
