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
Route::get('/validate-token', function () {
    return ['data' => 'Token is valid', 'success' => true];
})->middleware('auth:admin');
Route::group(['prefix' => 'surat', 'middleware' => ['auth:admin']], function () {
    Route::get('/jumlah_arsip', [SuratController::class, 'jumlah'])->name('jumlahArsip');
    Route::get('/arsip', [SuratController::class, 'arsip'])->name('arsip');
});
