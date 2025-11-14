<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    use HasFactory;
    protected $table = 'profile_sekolah';
    protected $fillable = [
        'nama_sekolah',
        'kepsek',
        'jam_masuk',
        'auto_alpa',
        'notif_wa'
    ];

    public $timestamps = false; //  mencegah Laravel otomatis set updated_at
}
