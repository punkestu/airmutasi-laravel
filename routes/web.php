<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PersonelController;
// use App\Http\Controllers\Rotasi\HomeController as RotasiHomeController;
use App\Http\Controllers\Rotasi\CabangController as RotasiCabangController;
use App\Http\Controllers\Rotasi\SelektifAdminController as RotasiSelektifAdminController;
use App\Http\Controllers\Rotasi\PengajuanController as RotasiPengajuanController;
use App\Http\Controllers\TaskController;
use App\Models\Cabang;
use App\Models\Personel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $personelCount = Personel::count();
    $cabangs = Cabang::select('thumbnail_url')->get();
    return view('welcome', ["personel" => $personelCount, "cabangs" => $cabangs]);
})->name('landing');

Route::middleware("guest")->get("/login", [AuthController::class, 'loginView'])->name('login');
Route::middleware("guest")->post("/login", [AuthController::class, 'login']);
Route::middleware("auth:web")->get("/logout", [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'download', 'middleware' => ["auth:web"]], function () {
    Route::middleware('is.admin')->get('/pengajuan/{id}', [RotasiPengajuanController::class, 'document']);
});

Route::group(['prefix' => 'kelas', 'middleware' => [
    'auth:web',
    'is.admin'
]], function () {
    Route::get('/', [KelasController::class, 'index'])->name('kelas');
    Route::post('/', [KelasController::class, 'input']);
    Route::get('/{cabang_id}/{kelas_id}/delete', [KelasController::class, 'destroy']);
});

Route::group(['prefix' => 'akun', 'middleware' => [
    'auth:web'
]], function () {
    Route::get("/", [AccountController::class, 'index'])->name('akun');
    Route::group(['prefix' => 'add', 'middleware' => [
        'is.admin'
    ]], function () {
        Route::get('/', [AccountController::class, 'inputView']);
        Route::post('/', [AccountController::class, 'input']);
    });
    Route::get('/edit', [AccountController::class, 'updateView']);
    Route::post('/edit', [AccountController::class, 'update']);
    Route::middleware(['is.admin'])->post('/assign', [AccountController::class, 'assign']);
    Route::middleware(['is.admin'])->post('/jabatan', [JabatanController::class, 'store']);
    Route::middleware(['is.admin'])->get('/jabatan/{id}/delete', [JabatanController::class, 'destroy']);
});

Route::group(['prefix' => 'personel', 'middleware' => [
    'auth:web'
]], function () {
    Route::middleware(['is.admin'])->get("/", [PersonelController::class, 'index'])->name('personel');
    Route::middleware(['is.admin'])->post("/import", [PersonelController::class, 'importAllWithCsv'])->name('personel.import');
    Route::middleware(['is.admin'])->get('/konsep', [PersonelController::class, 'konsep'])->name("konsep");
    Route::middleware(['is.admin'])->get('/task', [TaskController::class, 'index'])->name("task");
    Route::get('/task/{id}', [TaskController::class, 'detail'])->name("task.detail");
    Route::middleware(['is.admin'])->post('/konsep', [PersonelController::class, 'uploadKonsep']);
    Route::middleware(['is.admin'])->post('/task', [TaskController::class, 'store']);
    Route::get('/cabang/{id}', [PersonelController::class, 'cabang'])->name('personel.index');
    Route::group(['prefix' => 'add', 'middleware' => [
        'is.admin'
    ]], function () {
        Route::get('/', [PersonelController::class, 'inputView']);
        Route::post('/', [PersonelController::class, 'input']);
    });

    Route::middleware("is.admin")->get('/delete/{id}', [PersonelController::class, 'delete']);
    Route::middleware("is.admin")->get('/pensiun/{id}', [PersonelController::class, 'togglePensiun']);
    Route::middleware("is.admin")->post('/export', [PersonelController::class, 'importCsv']);
});

Route::group(['prefix' => 'rotasi', 'middleware' => [
    'auth:web'
]], function () {
    // Route::get('/', [RotasiHomeController::class, 'index'])->name('rotasi');
    Route::group(['prefix' => 'cabang'], function () {
        Route::group(['prefix' => 'input', 'middleware' => [
            'is.admin'
        ]], function () {
            Route::get('/', [RotasiCabangController::class, 'inputView']);
            Route::post('/', [RotasiCabangController::class, 'input']);

            Route::get('/{id}', [RotasiCabangController::class, 'updateView']);
            Route::post('/{id}', [RotasiCabangController::class, 'update']);

            Route::get('/{id}/delete', [RotasiCabangController::class, 'delete']);
        });

        Route::get('/', [RotasiCabangController::class, 'index'])->name('rotasi.cabang');
        Route::get("/{id}", [RotasiCabangController::class, 'detail']);
        Route::get('/konsep/{id}', [RotasiCabangController::class, 'konsep']);
        Route::post('/konsep/{id}', [RotasiCabangController::class, 'uploadKonsep']);
    });
    Route::group(['prefix' => 'pengajuan'], function () {
        Route::get('/', [RotasiPengajuanController::class, 'inputView']);
        Route::post('/', [RotasiPengajuanController::class, 'input']);
        Route::middleware("is.admin")->get('/{id}', [RotasiPengajuanController::class, 'updateView']);
        Route::middleware("is.admin")->post('/{id}', [RotasiPengajuanController::class, 'update']);
    });
    Route::group(['prefix' => 'selektif', 'middleware' => [
        'is.admin'
    ]], function () {
        Route::get('/', [RotasiSelektifAdminController::class, 'index'])->name('rotasi.selektif');
        Route::post("/{id}", [RotasiSelektifAdminController::class, 'selektif']);
    });
    Route::get("/notification", [NotificationController::class, 'index'])->name('rotasi.notifikasi');
    Route::get("/notification/task", [NotificationController::class, 'index_task'])->name('rotasi.notifikasi');
});

Route::group(['prefix' => 'promosi', 'middleware' => [
    'auth:web'
]], function () {
    Route::get('/', function () {
        return view('notimplemented');
    })->name('promosi');
});

Route::group(['prefix' => 'demosi', 'middleware' => [
    'auth:web'
]], function () {
    Route::get('/', function () {
        return view('notimplemented');
    })->name('promosi');
});
