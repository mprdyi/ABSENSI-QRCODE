<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'id_kelas',
        'nama',
        'face_descriptor',
        'jk',
    ];

    /*public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'id_kelas');
    }*/

    public function kelas()
    {
         // siswa.id_kelas (kode_kelas) → data_kelas.kode_kelas
         return $this->belongsTo(DataKelas::class, 'id_kelas', 'kode_kelas');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'nis', 'nis');
    }



    public function user()
    {
        return $this->hasOne(User::class, 'email', 'nis');
    }


}
