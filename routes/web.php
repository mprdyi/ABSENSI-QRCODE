<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKelasController;





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


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//route data siswa
Route::get('/data-siswa', [SiswaController::class, 'index'])->name('admin.data-master.data-siswa');
Route::post('/data-siswa/store', [SiswaController::class, 'store'])->name('data-siswa.store');
Route::delete('/data-siswa/{id}', [SiswaController::class, 'destroy'])->name('data-siswa.destroy');
Route::get('/data-siswa/{id}/edit', [SiswaController::class, 'edit'])->name('edit-data-siswa.edit');
Route::put('/data-siswa/{id}', [SiswaController::class, 'update'])->name('edit-data-siswa.update');
Route::get('/cari-siswa', [SiswaController::class, 'search'])->name('cari-siswa');


//route kelas
Route::get('/data-kelas', [DataKelasController::class, 'index'])->name('admin.data-master.data-kelas');
Route::post('/data-kelas/store', [DataKelasController::class, 'store'])->name('data-kelas.store');
Route::delete('/data-kelas/{id}', [DataKelasController::class, 'destroy'])->name('data-kelas.destroy');
Route::get('/edit-data-kelas/{id}', [DataKelasController::class, 'edit'])->name('data-kelas.edit');
Route::put('/edit-data-kelas/{id}', [DataKelasController::class, 'update'])->name('data-kelas.update');





