<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DashboardController;





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

Route::get('/data-siswa', [SiswaController::class, 'index'])->name('admin.data-master.data-siswa');
Route::post('/data-siswa/store', [SiswaController::class, 'store'])->name('data-siswa.store');
Route::delete('/data-siswa/{id}', [SiswaController::class, 'destroy'])->name('data-siswa.destroy');
Route::get('/data-siswa/{id}/edit', [SiswaController::class, 'edit'])->name('edit-data-siswa.edit');
Route::put('/data-siswa/{id}', [SiswaController::class, 'update'])->name('edit-data-siswa.update');
Route::get('/cari-siswa', [SiswaController::class, 'search'])->name('cari-siswa');



