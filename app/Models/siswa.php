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
        'jk',
    ];

    public function kelas()
    {
        return $this->belongsTo(DataKelas::class, 'id_kelas');
    }
}
