<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\Rotasi\CabangController as RotasiCabangController;
use App\Http\Controllers\API\Rotasi\PengajuanController as RotasiPengajuanController;
use App\Http\Controllers\PersonelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'rotasi'], function () {
    Route::get('/cabang-induk', [RotasiCabangController::class, 'listInduk']);
    Route::get('/cabang', [RotasiCabangController::class, 'all']);
    Route::get('/cabang-summary/{id}', [RotasiCabangController::class, 'summary']);
    Route::get('/cabang-search', [RotasiCabangController::class, 'search']);
    Route::get('/cabang-kelas/{kelas}', [RotasiCabangController::class, 'inKelas']);
    Route::get('/cabang-same-kelas/{id}', [RotasiCabangController::class, 'inSameKelas']);
    Route::get('/posisi', [RotasiCabangController::class, 'searchPosisi']);
});

Route::group(['prefix' => 'pengajuan'], function () {
    Route::get('/{id}', [RotasiPengajuanController::class, 'byId']);
});

Route::get("/struktur-cabang", [RotasiCabangController::class, 'tree']);
Route::post("/struktur-cabang/edit", [RotasiCabangController::class, 'editNode']);
Route::delete("/struktur-cabang/delete/{cabang_id}", [RotasiCabangController::class, 'deleteNode']);
Route::get("/personel", [PersonelController::class, 'search_by_nik']);

Route::post('/upload-doc', [FileController::class, 'uploadDoc']);
