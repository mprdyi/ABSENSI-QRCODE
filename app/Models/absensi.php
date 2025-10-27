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
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',


    ];


    // Absensi.php

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nis', 'nis');
    }

}
