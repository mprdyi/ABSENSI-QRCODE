<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $totalHadir      = ($rekapAbsensi['Hadir'] ?? 0);
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
            ->orderByDesc('rata_rata')
            ->limit(10)
            ->get();

        // === GRAFIK MINGGUAN (Senin–Jumat) ===
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek   = $startOfWeek->copy()->addDays(4)->endOfDay(); // sampai Jumat

        $grafikMingguan = Absensi::selectRaw('WEEKDAY(tanggal) as hari, COUNT(*) as total')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        // Susun urutan Senin–Jumat (0=Senin, 4=Jumat)
        $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $dataHadir = [];

        foreach (range(0, 4) as $i) {
            $dataHadir[] = $grafikMingguan->firstWhere('hari', $i)?->total ?? 0;
        }

        // NOTIF CARD UNTUK SISWA
            $statusAbsensi = null;
            if (Auth::user()->role === 'siswa' && Auth::user()->siswa) {
                $hariIni = Carbon::today();
                $cekAbsen = Absensi::where('nis', Auth::user()->siswa->nis)->whereDate('tanggal', $hariIni)->first();
                if ($cekAbsen) {
                    $statusAbsensi = $cekAbsen->status;
                }
            }

        //REKAP PUNYA SISWA MASING MASING
        $rekapSaya = collect([]);

        if (Auth::user()->role === 'siswa' && Auth::user()->siswa) {
            $rekapSaya = Absensi::where('nis', Auth::user()->siswa->nis)
                            ->selectRaw('status, count(*) as total')
                            ->groupBy('status')
                            ->pluck('total', 'status');
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
            'statusAbsensi'       => $statusAbsensi,
            'rekapSaya'          => $rekapSaya
        ]);
    }

    public function login(){
        return view('login');
    }

}
