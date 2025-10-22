<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\QrController;





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
Route::get('/get-wali-kelas/{id}', [SiswaController::class, 'getWaliKelas']);

Route::post('/import-siswa', [CsvController::class, 'importSiswa'])->name('import.siswa');
Route::get('/qrcode/{nis}', [QrController::class, 'show'])->name('qrcode.show');
Route::get('/qrcode/{nis}/pdf', [QrController::class, 'downloadPdf'])->name('qrcode.pdf');
Route::get('/qrcode/pdf/all', [QrController::class, 'downloadAllPdf'])->name('qrcode.pdf.all');





//route kelas
Route::get('/data-kelas', [DataKelasController::class, 'index'])->name('admin.data-master.data-kelas');
Route::post('/data-kelas/store', [DataKelasController::class, 'store'])->name('data-kelas.store');
Route::delete('/data-kelas/{id}', [DataKelasController::class, 'destroy'])->name('data-kelas.destroy');
Route::get('/edit-data-kelas/{id}', [DataKelasController::class, 'edit'])->name('data-kelas.edit');
Route::put('/edit-data-kelas/{id}', [DataKelasController::class, 'update'])->name('data-kelas.update');
Route::get('/cari-kelas', [DataKelasController::class, 'search'])->name('cari-kelas');
Route::post('/import-kelas', [CsvController::class, 'importKelas'])->name('import.kelas');



//route guru
Route::get('/data-guru', [GuruController::class, 'index'])->name('admin.data-master.data-guru');
Route::post('/data-guru/store', [GuruController::class, 'store'])->name('data-guru.store');
Route::delete('/data-guru/{id}', [GuruController::class, 'destroy'])->name('data-guru.destroy');
Route::get('/data-guru/edit/{id}', [GuruController::class, 'edit'])->name('data-guru.edit');
Route::put('/edit-data-guru/{id}', [GuruController::class, 'update'])->name('data-guru.update');
Route::get('/cari-guru', [GuruController::class, 'search'])->name('cari-guru');
Route::get('/data-guru-jumlah', [GuruController::class, 'countGuru'])->name('data-guru.jumlah');
Route::post('/import-guru', [CsvController::class, 'importGuru'])->name('import.guru');

//QR
Route::get('/qrcode', [QrController::class, 'index']);






