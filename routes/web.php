<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DaerahController;
use App\Http\Controllers\OrtuController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GenerusController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Menu Kepala Keluarga
    Route::get('/ortu', [OrtuController::class, 'index']);
    Route::post('/ortu', [OrtuController::class, 'store']);
    Route::post('/ortu/edit/{id}', [OrtuController::class, 'update']);
    Route::delete('/ortu/delete/{id}', [OrtuController::class, 'destroy']);

    // Menu Jamaah
    Route::get('/jamaah', [JamaahController::class, 'index']);
    Route::get('jamaah/export/', [JamaahController::class, 'export']);
    Route::post('/jamaah', [JamaahController::class, 'store']);
    Route::post('/jamaah/edit/{id}', [JamaahController::class, 'update']);
    Route::delete('/jamaah/delete/{id}', [JamaahController::class, 'destroy']);

    // Menu Generus
    Route::get('/generus', [GenerusController::class, 'index']);
    Route::get('generus/export/', [GenerusController::class, 'export'])->name('generus.export');

    Route::post('/generus', [GenerusController::class, 'store']);
    Route::post('/generus/edit/{id}', [GenerusController::class, 'update']);
    Route::delete('/generus/delete/{id}', [GenerusController::class, 'destroy']);


    // Menu Daerah
    Route::get('/daerah', [DaerahController::class, 'index']);

    // Menu Desa
    Route::get('/desa', [DesaController::class, 'index']);
    Route::post('/desa', [DesaController::class, 'store']);
    Route::post('/desa/edit/{id}', [DesaController::class, 'update']);
    Route::delete('/desa/delete/{id}', [DesaController::class, 'destroy']);

    // Menu Kelompok
    Route::get('/kelompok', [KelompokController::class, 'index']);
    Route::get('/kelompok/get-by-desa/{id}', [KelompokController::class, 'getByDesa']);
    Route::post('/kelompok', [KelompokController::class, 'store']);
    Route::post('/kelompok/edit/{id}', [KelompokController::class, 'update']);
    Route::delete('/kelompok/delete/{id}', [KelompokController::class, 'destroy']);

    // Menu Kelas
    Route::get('/kls', [KelasController::class, 'index']);
    Route::post('/kls', [KelasController::class, 'store']);
    Route::post('/kls/edit/{id}', [KelasController::class, 'update']);
    Route::delete('/kls/delete/{id}', [KelasController::class, 'destroy']);

    // Menu Pekerjaan
    Route::get('/pekerjaan', [PekerjaanController::class, 'index']);
    Route::post('/pekerjaan', [PekerjaanController::class, 'store']);
    Route::post('/pekerjaan/edit/{id}', [PekerjaanController::class, 'update']);
    Route::delete('/pekerjaan/delete/{id}', [PekerjaanController::class, 'destroy']);

    // Menu User
    Route::get('/user', [userController::class, 'index']);
    Route::post('/user', [userController::class, 'store']);
    Route::post('/user/edit/{id}', [userController::class, 'update']);
    Route::delete('/user/delete/{id}', [userController::class, 'destroy']);

    // Menu Siswa
    Route::get('/siswa', [SiswaController::class, 'index']);
});

Route::get('/login', [AuthenticationController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthenticationController::class, 'store'])->name('login.submit');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
