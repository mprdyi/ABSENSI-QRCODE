<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKelas extends Model
{
    use HasFactory;
    protected $table = 'data_kelas';

    protected $fillable = [
        'id_wali_kelas',
        'kode_kelas',
        'kelas',

    ];

    public function waliKelas()
    {
         // belongsTo(Model tujuan, foreignKey di tabel ini, ownerKey di tabel tujuan)
         return $this->belongsTo(Guru::class, 'id_wali_kelas', 'nip');
    }

    /*public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }*/


    public function siswa()
    {
       // data_kelas.kode_kelas → siswa.id_kelas
       return $this->hasMany(Siswa::class, 'id_kelas', 'kode_kelas');
    }



}
