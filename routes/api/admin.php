<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
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
    return ['data' => 'Token is valid'];
})->middleware('auth:admin');
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin']], function () {

});
