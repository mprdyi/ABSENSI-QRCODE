<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\QrAbsensiController;
use App\Http\Controllers\IzinKelasController;
use App\Http\Controllers\Auth\AuthController;





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


// LOGIN
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
//route pengaturan sekolah
Route::get('/profil-sekolah', [ProfilController::class, 'index'])->name('profil-sekolah.index');
Route::put('/profil-sekolah/update', [ProfilController::class, 'update'])->name('profil-sekolah.update');

//route data siswa
Route::get('/data-siswa', [SiswaController::class, 'index'])->name('admin.data-master.data-siswa');
Route::post('/data-siswa/store', [SiswaController::class, 'store'])->name('data-siswa.store');
Route::delete('/data-siswa/{id}', [SiswaController::class, 'destroy'])->name('data-siswa.destroy');
Route::get('/data-siswa/{id}/edit', [SiswaController::class, 'edit'])->name('edit-data-siswa.edit');
Route::put('/data-siswa/{id}', [SiswaController::class, 'update'])->name('edit-data-siswa.update');
Route::get('/cari-siswa', [SiswaController::class, 'search'])->name('cari-siswa');
Route::get('/get-wali-kelas/{id}', [SiswaController::class, 'getWaliKelas']);


// KODE NIS QRCODE
Route::post('/import-siswa', [CsvController::class, 'importSiswa'])->name('import.siswa');
Route::get('/qrcode/{nis}', [QrController::class, 'show'])->name('qrcode.show');
Route::get('/qrcode/{nis}/pdf', [QrController::class, 'downloadPdf'])->name('qrcode.pdf');
Route::post('/download-qrcode-kelas', [QrController::class, 'downloadByKelas'])->name('download.qrcode.kelas');



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

//Absensi Qr
Route::get('/data-absen-siswa', [QrAbsensiController::class, 'index'])->name('data-absensi-siswa.data');
Route::post('/absensi/store', [QrAbsensiController::class, 'store'])->name('absensi.store');
Route::get('/data-absensi-qr', [QrAbsensiController::class, 'dataAbsensiQr'])->name('data-absensi-siswa.Qr');

//Laporan Absensi
Route::get('/laporan-harian', [LaporanController::class, 'index'])->name('laporan-harian.harian');
Route::get('/laporan-Arsip', [LaporanController::class, 'arsip'])->name('arsip-absensi.all');
Route::get('/Edit-Absensi-Siswa/{id}', [LaporanController::class, 'edit'])->name('edit-absen.data');
Route::put('/Edit-Absensi-Siswa/{id}', [LaporanController::class, 'update'])->name('edit-absen.update');
Route::get('/filter-absen', [LaporanController::class, 'Filter'])->name('Filter.kelas');
Route::get('/absensi/filter', [LaporanController::class, 'CariArsip'])->name('cari-data.arsip');
Route::get('/Download-Backup', [LaporanController::class, 'DownloadBackup'])->name('download-backup.laporan');

//IZIN MENINGGALKAN KELAS
Route::get('/Izin-Meninggalkan-Kelas', [IzinKelasController::class, 'index'])->name('izin.kelas');
Route::get('/get-siswa-by-kelas/{id_kelas}', [IzinKelasController::class, 'getSiswaByKelas'])->name('getSiswaByKelas');
Route::post('/izin/store', [IzinKelasController::class, 'store'])->name('izin.store');
Route::get('/surat-izin/download/{nis}', [IzinKelasController::class, 'DownloadSurat'])->name('surat-izin.download');
Route::get('/cari/data/izin', [IzinKelasController::class, 'cari'])->name('cari-data.izin');

});


