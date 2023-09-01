<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Surat\SuratController;
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
Route::post('logout', [AdminAuthController::class, 'logout'])->middleware('auth:jwt')->name('adminLogout');
Route::get('/validate-token', function () {
    return ['data' => 'Token is valid', 'success' => true];
})->middleware('auth:admin');
Route::group(['prefix' => 'surat', 'middleware' => ['auth:admin']], function () {
    Route::get('/jumlah_arsip', [SuratController::class, 'jumlah'])->name('jumlahArsip');
    Route::get('/arsip', [SuratController::class, 'arsip'])->name('arsip');
    Route::get('/show', [SuratController::class, 'show'])->name('show');
    Route::get('/permohonan', [SuratController::class, 'permohonan'])->name('show');
    Route::get('/mandiri', [SuratController::class, 'mandiri'])->name('show');
    Route::get('/download/{id}', [SuratController::class, 'download'])->name('arsipdownload')->where('id', '[0-9]+');
});
