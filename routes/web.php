<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DaerahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\GenerusController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\KurikulumController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Menu Generus
    Route::prefix('generus')->name('generus.')->group(function () {
        Route::get('/', [GenerusController::class, 'index'])->name('index');
        Route::get('/export/', [GenerusController::class, 'export'])->name('export');
        Route::get('/exportTemplate/', [GenerusController::class, 'exportTemplate'])->name('exportTemplate');
        Route::post('/import/', [GenerusController::class, 'import'])->name('import');

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

    Route::prefix('kelompok')->name('kelompok.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [KelompokController::class, 'index'])->name('index');
        Route::get('/get-by-desa/{id}', [KelompokController::class, 'getByDesa'])->name('getByDesa');
        Route::post('/', [KelompokController::class, 'store'])->name('store');
        Route::post('/edit/{id}', [KelompokController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KelompokController::class, 'destroy'])->name('destroy');
    });

    // Menu Kelas
    Route::prefix('kls')->name('kls.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('index');
        Route::post('/', [KelasController::class, 'store'])->name('store');
        Route::post('/edit/{id}', [KelasController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [KelasController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('kurikulum')->name('kurikulum.')->middleware('jabatan.daerah')->group(function () {
        Route::get('/', [KurikulumController::class, 'index'])->name('index');
        Route::post('/', [KurikulumController::class, 'store'])->name('store');
        Route::delete('/delete/{id}', [KurikulumController::class, 'destroy'])->name('destroy');
        Route::get('/download/{id}', [KurikulumController::class, 'download'])->name('download');
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

    // Menu Siswa
    Route::get('/siswa', [SiswaController::class, 'index']);
});

Route::get('/login', [AuthenticationController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthenticationController::class, 'store'])->name('login.submit');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
