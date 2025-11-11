<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DaerahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GenerusController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\MusyawarahController;
use App\Http\Controllers\PengajianController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TimProkerController;
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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Menu Generus
    Route::prefix('generus')->name('generus.')->group(function () {
        Route::get('/', [GenerusController::class, 'index'])->name('index');
        Route::get('/export/', [GenerusController::class, 'export'])->name('export');
        Route::get('/exportTemplate/', [GenerusController::class, 'exportTemplate'])->name('exportTemplate');
        Route::post('/import/', [GenerusController::class, 'import'])->name('import');
        Route::get('/data', [GenerusController::class, 'getData'])->name('data');

        Route::post('/', [GenerusController::class, 'store']);
        Route::post('/edit/{id}', [GenerusController::class, 'update']);
        Route::delete('/delete/{id}', [GenerusController::class, 'destroy']);
    });


    // Menu Daerah
    Route::get('/daerah', [DaerahController::class, 'index']);

    // Menu Desa
    Route::prefix('desa')->name('desa.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [DesaController::class, 'index'])->name('index');
        Route::post('/', [DesaController::class, 'store'])->name('store');
        Route::post('/edit/{id}', [DesaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DesaController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('kelompok')->name('kelompok.')->group(function () {
        Route::get('/get-by-desa/{id}', [KelompokController::class, 'getByDesa'])->name('getByDesa');
        Route::get('/', [KelompokController::class, 'index'])->middleware('jabatan.daerah')->name('index');
        Route::post('/', [KelompokController::class, 'store'])->middleware('jabatan.daerah')->name('store');
        Route::post('/edit/{id}', [KelompokController::class, 'update'])->middleware('jabatan.daerah')->name('update');
        Route::delete('/delete/{id}', [KelompokController::class, 'destroy'])->middleware('jabatan.daerah')->name('destroy');
    });

    // Menu Kelas
    Route::prefix('kls')->name('kls.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('index');
        Route::post('/', [KelasController::class, 'store'])->name('store');
        Route::post('/edit/{id}', [KelasController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KelasController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kurikulum')->name('kurikulum.')->group(function () {
        Route::get('/', [KurikulumController::class, 'index'])->name('index');
        Route::post('/', [KurikulumController::class, 'store'])->middleware('jabatan.daerah')->name('store');
        Route::delete('/delete/{id}', [KurikulumController::class, 'destroy'])->middleware('jabatan.daerah')->name('destroy');
        Route::get('/download/{id}', [KurikulumController::class, 'download'])->name('download');
    });

    Route::prefix('musyawarah')->name('musyawarah.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [MusyawarahController::class, 'index'])->name('index');
        Route::post('/', [MusyawarahController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [MusyawarahController::class, 'destroy'])->name('destroy');
        Route::put('/update/{id}', [MusyawarahController::class, 'update'])->name('update');
        Route::get('/download/{id}', [MusyawarahController::class, 'download'])->name('download');
    });

    // Menu User
    Route::prefix('user')->name('user.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [userController::class, 'index'])->name('index');
        Route::get('/profile', [userController::class, 'profile'])->name('profile');
        Route::post('/', [userController::class, 'store'])->name('store');
        Route::post('/edit/{id}', [userController::class, 'update'])->name('update');
        Route::post('/profile/edit/{id}', [userController::class, 'updateProfile'])->name('profile.update');
        Route::delete('/delete/{id}', [userController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pengajian')->name('pengajian.')->group(function () {
        Route::get('/', [PengajianController::class, 'index'])->name('pengajian.index');
        Route::get('/export/{tahun}', [PengajianController::class, 'export'])->name('report.export');
        Route::get('/{pengajian}/edit/', [PengajianController::class, 'edit'])->name('edit');
        Route::post('/', [PengajianController::class, 'store'])->name('store');
        Route::patch('/{absen}/edit/', [PengajianController::class, 'update'])->name('update');
        Route::delete('/delete/{pengajian}', [PengajianController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sarpras')->name('sarpras.')->group(function () {
        Route::get('/', [SarprasController::class, 'index'])->name('index');
        Route::post('/', [SarprasController::class, 'store'])->name('store');
        Route::put('/update/{id}', [SarprasController::class, 'update'])->name('update');
        Route::delete('/delete/{sarpras}', [SarprasController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('tim-proker')->name('tim-proker.')->middleware('jabatan.daerah')->group(function () {
        Route::delete('/delete/{proker}', [TimProkerController::class, 'destroy'])->name('destroy');
        Route::resource('/', TimProkerController::class)->parameters(['' => 'tim_proker'])->except('create', 'show', 'edit', 'destroy');
    });

    Route::prefix('proker')->name('proker.')->middleware('jabatan.daerah')->group(function () {
        Route::delete('/delete/{proker}', [ProkerController::class, 'destroy'])->name('destroy');
        Route::resource('/', ProkerController::class)->parameters(['' => 'proker'])->except('create', 'show', 'edit', 'destroy');
    });
});

Route::get('/login', [AuthenticationController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthenticationController::class, 'store'])->name('login.submit');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
