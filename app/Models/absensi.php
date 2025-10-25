<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'data_absensi';
    protected $fillable = [
        'nis',
        'id_kelas',
        'id_wali_kelas',
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',


    ];

// Absensi.php
public function siswa() {
    return $this->belongsTo(Siswa::class, 'nis', 'nis');
}
public function kelas() {
    return $this->belongsTo(DataKelas::class, 'id_kelas', 'id');
}
public function waliKelas() {
    return $this->belongsTo(Guru::class, 'id_wali_kelas', 'id');
}

}
