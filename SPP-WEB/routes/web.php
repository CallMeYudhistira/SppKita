<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SppController;
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

Route::middleware(['guest:siswa', 'guest:petugas'])->group(function () {
    Route::get('/', function () {
        return redirect('/login');
    });
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login/proses', [AuthController::class, 'loginProses']);
});


Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware(['petugas'])->group(function () {
    Route::get('/petugas/beranda', [HomeController::class, 'petugas']);

    Route::prefix('/kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index']);
        Route::post('/tambah', [KelasController::class, 'tambah']);
        Route::put('/ubah/{id}', [KelasController::class, 'ubah']);
        Route::delete('/hapus/{id}', [KelasController::class, 'hapus']);
    });

    Route::prefix('/spp')->group(function () {
        Route::get('/', [SppController::class, 'index']);
        Route::post('/tambah', [SppController::class, 'tambah']);
        Route::put('/ubah/{id}', [SppController::class, 'ubah']);
        Route::delete('/hapus/{id}', [SppController::class, 'hapus']);
    });

    Route::prefix('/siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index']);
        Route::post('/tambah', [SiswaController::class, 'tambah']);
        Route::put('/ubah/{id}', [SiswaController::class, 'ubah']);
        Route::delete('/hapus/{id}', [SiswaController::class, 'hapus']);
    });

    Route::prefix('/petugas')->group(function () {
        Route::get('/', [PetugasController::class, 'index']);
        Route::post('/tambah', [PetugasController::class, 'tambah']);
        Route::put('/ubah/{id}', [PetugasController::class, 'ubah']);
        Route::delete('/hapus/{id}', [PetugasController::class, 'hapus']);
    });

    Route::prefix('/pembayaran')->group(function () {
        Route::get('/', [PembayaranController::class, 'index']);
        Route::post('/bayar/{nisn}', [PembayaranController::class, 'bayar']);
        Route::get('/riwayat', [PembayaranController::class, 'riwayat']);
        Route::get('/cetak/{nisn}/{tanggal}', [PembayaranController::class, 'cetak']);
    });
});

Route::middleware(['siswa'])->group(function () {
    Route::get('/siswa/beranda', [HomeController::class, 'siswa']);
});
