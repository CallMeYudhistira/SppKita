<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KelasController;
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

    Route::prefix('/kelas')->group(function(){
        Route::get('/', [KelasController::class, 'index']);
        Route::post('/tambah', [KelasController::class, 'tambah']);
        Route::put('/ubah/{id}', [KelasController::class, 'ubah']);
        Route::delete('/hapus/{id}', [KelasController::class, 'hapus']);
    });
});

Route::middleware(['siswa'])->group(function () {
    Route::get('/siswa/beranda', [HomeController::class, 'siswa']);
});
