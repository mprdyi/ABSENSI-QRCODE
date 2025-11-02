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

        // ===  REKAP JUMLAH SISWA ===
        $rekapJK = Siswa::select('jk')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('jk')
            ->pluck('total', 'jk');

        $totalLaki       = $rekapJK['L'] ?? 0;
        $totalPerempuan  = $rekapJK['P'] ?? 0;
        $totalSiswa      = $totalLaki + $totalPerempuan;

        // ===  REKAP ABSENSI HARI INI ===
        $rekapAbsensi = Absensi::whereDate('tanggal', $hariIni)
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalHadir      = ($rekapAbsensi['Hadir'] ?? 0) + ($rekapAbsensi['Terlambat'] ?? 0);
        $totalIzin       = $rekapAbsensi['Izin'] ?? 0;
        $totalSakit      = $rekapAbsensi['Sakit'] ?? 0;
        $totalAlpha      = $rekapAbsensi['Alpha'] ?? 0;
        $totalTerlambat  = $rekapAbsensi['Terlambat'] ?? 0;
        $totalAbsensiHariIni = array_sum($rekapAbsensi->toArray());

        // === 10 SISWA PALING SERING TERLAMBAT ===
        $topTerlambat = Absensi::with('siswa.kelas.waliKelas')
            ->select('nis')
            ->selectRaw('COUNT(*) as jumlah')
            ->selectRaw('ROUND(AVG(CAST(REGEXP_REPLACE(keterangan, "[^0-9]", "") AS UNSIGNED))) as rata_rata')
            ->where('status', 'Terlambat')
            ->groupBy('nis')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();

        // === GRAFIK MINGGUAN (Senin–Jumat) ===
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = Carbon::now()->endOfWeek(Carbon::FRIDAY);

        $grafikMingguan = Absensi::selectRaw('DAYNAME(tanggal) as hari, COUNT(*) as total')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Susun urutan Senin–Jumat
        $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dataHadir = [];
        foreach ($labels as $hari) {
            $dataHadir[] = $grafikMingguan->firstWhere('hari', $hari)?->total ?? 0;
        }

        // === KIRIM KE VIEW ===
        return view('dashboard', [
            'totalSiswa'          => $totalSiswa,
            'totalLaki'           => $totalLaki,
            'totalPerempuan'      => $totalPerempuan,
            'totalAbsensiHariIni' => $totalAbsensiHariIni,
            'totalHadir'          => $totalHadir,
            'totalIzin'           => $totalIzin,
            'totalSakit'          => $totalSakit,
            'totalAlpha'          => $totalAlpha,
            'totalTerlambat'      => $totalTerlambat,
            'topTerlambat'        => $topTerlambat,
            'labels'              => $labels,
            'dataHadir'           => $dataHadir,
        ]);
    }
}
