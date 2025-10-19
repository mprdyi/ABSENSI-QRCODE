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
        return $this->belongsTo(Guru::class, 'id_wali_kelas');
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }



}
