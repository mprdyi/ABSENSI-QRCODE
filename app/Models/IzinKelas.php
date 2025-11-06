<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IzinKelas extends Model
{
    use HasFactory;
    protected $table = 't_izin_keluar_kelas';
    protected $fillable = [
        'nis',
        'kode_kelas',
        'waktu_izin',
        'waktu_habis',
        'keperluan',
        'keterangan'

    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }
}
