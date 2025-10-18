<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total siswa
        $totalSiswa = Siswa::count();

        // Kirim ke view
        return view('dashboard', compact('totalSiswa'));
    }

    

}
