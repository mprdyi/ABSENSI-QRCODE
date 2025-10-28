<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        // Hitung total siswa
        $rekapJK = Siswa::select('jk')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jk')
            ->pluck('total', 'jk');

        $totalLaki = $rekapJK['L'] ?? 0;
        $totalPerempuan = $rekapJK['P'] ?? 0;
        $totalSiswa = $totalLaki + $totalPerempuan;

        $totalAbsensiHariIni = Absensi::whereDate('tanggal', $hariIni)->count();

        $rekap = Absensi::whereDate('tanggal', $hariIni)
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

        $totalSakit = $rekap['Sakit'] ?? 0;
        $totalHadir = $rekap['Sakit'] ?? 0;
        $totalTerlambat = $rekap['Terlambat'] ?? 0;
        $totalIzin = $rekap['Izin'] ?? 0;
        $totalAlpha = $rekap['Alpha'] ?? 0;


        $topTerlambat = Absensi::with('siswa.kelas.waliKelas')
        ->select('nis')
        ->selectRaw('COUNT(*) as jumlah')
        ->selectRaw('ROUND(AVG(CAST(REGEXP_REPLACE(keterangan, "[^0-9]", "") AS UNSIGNED))) as rata_rata')
        ->where('status', 'Terlambat')
        ->groupBy('nis')
        ->orderByDesc('jumlah')
        ->limit(10)
        ->get();


        $data = [
            'totalSiswa' => $totalSiswa,
            'totalLaki' => $totalLaki,
            'totalPerempuan' => $totalPerempuan,
            'totalAbsensiHariIni' => $totalAbsensiHariIni,
            'totalSakit' => $totalSakit,
            'totalHadir' => $totalHadir,
            'totalIzin' => $totalIzin,
            'totalAlpha' => $totalAlpha,
            'totalTerlambat' => $totalTerlambat,
            'topTerlambat' =>   $topTerlambat

        ];


        // Kirim ke view
        return view('dashboard', $data);
    }



}
