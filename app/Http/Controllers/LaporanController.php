<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\DataKelas;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $data_absensi = Absensi::with('siswa.kelas.waliKelas')
        ->whereDate('tanggal', Carbon::today())
        ->orderBy('jam_masuk', 'desc')
        ->get();


        // === REKAP KELAS X ===
        $rekapKelasX = Absensi::join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.id')
        ->whereDate('data_absensi.tanggal', $hariIni)
        ->where('data_kelas.kelas', 'LIKE', 'X %')
        ->select([
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        ])
        ->first();

        // === REKAP KELAS XI ===
        $rekapKelasXI = Absensi::join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.id')
        ->whereDate('data_absensi.tanggal', $hariIni)
        ->where('data_kelas.kelas', 'LIKE', 'XI %')
        ->select([
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        ])
        ->first();

        // === REKAP KELAS XII ===
        $rekapKelasXII = Absensi::join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.id')
        ->whereDate('data_absensi.tanggal', $hariIni)
        ->where('data_kelas.kelas', 'LIKE', 'XII %')
        ->select([
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN data_absensi.status = 'Terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        ])
        ->first();

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
            'topTerlambat' =>   $topTerlambat,
            'rekapKelasX' => $rekapKelasX,
            'rekapKelasXI' => $rekapKelasXI,
            'rekapKelasXII' => $rekapKelasXII,
            'data_absensi' => $data_absensi

        ];

        return view ('laporan.harian', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function Filter(Request $request)
    {
        $query = Absensi::with('siswa.kelas.waliKelas')
        ->whereDate('tanggal', Carbon::today())
        ->orderBy('jam_masuk', 'desc');

    if ($request->filled('kelas')) {
        $query->whereHas('siswa.kelas', function($q) use ($request) {
            $q->where('kelas', $request->kelas);
        });
    }

    $data_absensi = $query->get();
    $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();

    return view('absensi-siswa.data-absensi.edit-absensi', [
        'data_absensi' => $data_absensi,
        'data_kelas'   => $data_kelas,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $absensi = Absensi::with('siswa.kelas.waliKelas')->findOrFail($id);

        $data = [
            'item_absen' => $absensi
        ];
        return view('absensi-siswa.data-absensi.edit-absensi', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha,Terlambat',
        ]);

        $absen = Absensi::findOrFail($id);

        // Ubah status sesuai pilihan
        $absen->status = $validated['status'];

        // Kalau statusnya bukan Terlambat → ubah keterangan jadi "0 menit"
        if ($validated['status'] !== 'Terlambat') {
            $absen->keterangan = '0 menit';
        }else {
            $absen->keterangan = 'Error';
        }

        $absen->save();

        return redirect()->route('laporan-harian.harian')
                         ->with('success', 'Data absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
