<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'gurus';
    protected $fillable =[
        'nip',
        'nama_guru',
        'mapel',
        'no_hp',

    ];

    public function kelas()
{
 // hasMany(Model tujuan, foreignKey di tabel tujuan, localKey di tabel ini)
 return $this->hasMany(DataKelas::class, 'id_wali_kelas', 'nip');
}

}
