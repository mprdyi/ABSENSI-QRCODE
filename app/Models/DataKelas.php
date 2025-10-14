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

}
