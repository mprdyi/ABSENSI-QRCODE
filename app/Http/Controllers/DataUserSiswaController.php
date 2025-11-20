<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\DataKelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\User;
use App\Models\IzinKelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class DataUserSiswaController extends Controller
{
        public function dataSiswa()
        {
            $today = Carbon::today();
            $user = Auth::user();

            // Default variabel agar tidak error di view
            $siswaLogin = null;
            $kelasWali = null;
            $siswas = collect();

            // =========================================================
            // 1. JIKA SISWA LOGIN
            // =========================================================
            if ($user->role === 'siswa') {

                $nis = $user->email;

                // Ambil siswa yang sedang login + relasi kelas
                $siswaLogin = Siswa::with('kelas')
                    ->where('nis', $nis)
                    ->firstOrFail();

                // Ambil semua siswa di kelas yang sama
                $siswas = Siswa::with([
                    'user',
                    'kelas',
                    'absensi' => function ($q) use ($today) {
                        $q->whereDate('tanggal', $today)
                          ->select('id', 'nis', 'status', 'keterangan');
                    }
                ])
                ->where('id_kelas', $siswaLogin->id_kelas) // cocok kode kelas
                ->get();
            }

            // =========================================================
            // 2. JIKA WALI KELAS LOGIN (walkes)
            // =========================================================
            elseif ($user->role === 'walkes') {

                // Ambil guru + kelas yang dia pegang
                $guruLogin = Guru::with('kelas')
                    ->where('nip', $user->email)
                    ->firstOrFail();

                // Ambil kode kelas → KARENA siswas.id_kelas = kode_kelas (string)
                $kelasKode = $guruLogin->kelas->pluck('kode_kelas');

                // Ambil semua siswa berdasarkan kelas yang dia pegang
                $siswas = Siswa::with([
                    'user',
                    'kelas',
                    'absensi' => function ($q) use ($today) {
                        $q->whereDate('tanggal', $today)
                          ->select('id', 'nis', 'status', 'keterangan');
                    }
                ])
                ->whereIn('id_kelas', $kelasKode)
                ->get();

                // Simpan nama kelas untuk ditampilkan di view
                $kelasWali = $guruLogin->kelas->pluck('kelas')->implode(', ');
            }

            // =========================================================
            // 3. SORTING PRIORITAS STATUS ABSENSI
            // =========================================================
            $priorityOrder = ['alpha', 'izin', 'sakit', 'terlambat'];

            $siswas = $siswas->sortBy(function ($s) use ($priorityOrder) {
                $status = $s->absensi->first()->status ?? 'hadir';
                $index = array_search(strtolower($status), $priorityOrder);
                return $index === false ? count($priorityOrder) : $index;
            });

            // Hitung total status hari ini
                $totalAlpha = $siswas->where('absensi.0.status', 'Alpha')->count();
                $totalIzin = $siswas->where('absensi.0.status', 'Izin')->count();
                $totalSakit = $siswas->where('absensi.0.status', 'Sakit')->count();
                $totalTerlambat = $siswas->where('absensi.0.status', 'Terlambat')->count();


            // =========================================================
            // RETURN KE VIEW
            // =========================================================
            return view('km.data-absen-kelas', [
                'siswas'      => $siswas,
                'siswaLogin'  => $siswaLogin,
                'kelasWali'   => $kelasWali,
                'user'        => $user,
                'totalSakit'   =>$totalSakit,
                'totalIzin'   =>$totalIzin,
                'totalAlpha'   =>$totalAlpha,
                'totalTerlambat'   =>$totalTerlambat,
            ]);
        }


    public function update(Request $request, $id){
        $absensi = Absensi::findOrFail($id);
        $request->validate([
            'status' => 'required|in:izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui!');
    }


    // MENGAJUKAN ABSENSI SISWA MANDIRI
    public function ajukanAbsensi()
    {
        // Ambil semua kelas
        $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();

        // Kirim ke view
        return view('ajukan-absensi', compact('data_kelas'));
    }

    // Ambil siswa berdasarkan kelas (AJAX)
    public function getSiswaByKelas($id_kelas)
    {
        $siswas = Siswa::where('id_kelas', $id_kelas)
            ->orderBy('nama', 'asc')
            ->get(['nis', 'nama']);

        return response()->json($siswas);
    }

    public function submitAbsensi(Request $request)
{
    $request->validate([
        'kelas_ajukan' => 'required',
        'siswa_ajukan' => 'required',
        'status_ajukan' => 'required|in:izin,sakit',
        'keterangan_ajukan' => 'nullable|string'
    ]);

    $nis = $request->siswa_ajukan;

    // 1 Cek apakah siswa ada
    $siswa = Siswa::where('nis', $nis)->first();
    if (!$siswa) {
        return redirect()->back()->withErrors(['siswa_ajukan' => 'Siswa tidak ditemukan.']);
    }

    //  3Cek apakah hari ini sudah ada absensi "hadir" (patokan hari aktif)
    $adaHadirHariIni = Absensi::whereDate('tanggal', now()->toDateString())
        ->where('status', 'hadir')
        ->exists();

    if (!$adaHadirHariIni) {
        return redirect()->back()->withErrors([
            'status_ajukan' => 'Belum ada siswa yang hadir hari ini, sepertinya hari ini libur. Pengajuan izin/sakit tidak bisa dilakukan.'
        ]);
    }

    // 3 Cek apakah siswa sudah absen hari ini
    $sudahAbsen = Absensi::where('nis', $nis)
        ->whereDate('tanggal', now()->toDateString())
        ->exists();

    if ($sudahAbsen) {
        return redirect()->back()->withErrors([
            'siswa_ajukan' => 'Siswa ini sudah memiliki data absensi hari ini.'
        ]);
    }

    // 4️ Simpan data absensi izin/sakit
    Absensi::create([
        'nis' => $nis,
        'tanggal' => now()->format('Y-m-d'),
        'jam_masuk' => now()->format('H:i:s'),
        'status' => $request->status_ajukan,
        'keterangan' => $request->keterangan_ajukan,
    ]);

    return redirect()->back()->with('status', 'Absensi berhasil diajukan!');
}

public function downloadRekapDataKelas()
{
    $user = Auth::user();
    $tahun = now()->year;

    // =========================================================
    // 1. Tentukan kelas
    // =========================================================
    if ($user->role === 'walkes') {
        // Ambil guru + kelas yang dia pegang
        $guruLogin = Guru::with('kelas')->where('nip', $user->email)->firstOrFail();

        // Ambil kode kelas yang dia pegang
        $kelasKode = $guruLogin->kelas->pluck('kode_kelas');

        // Ambil semua siswa di kelas yang dia pegang
        $kelas = DataKelas::with(['waliKelas', 'siswa'])
            ->whereIn('kode_kelas', $kelasKode)
            ->first(); // kalau mau per kelas, bisa loop per kode_kelas
    } else {
        return back()->with('error', 'Hanya wali kelas yang bisa mengunduh rekap.');
    }

    if (!$kelas) {
        return back()->with('error', 'Kelas tidak ditemukan.');
    }

    $siswaList = $kelas->siswa;

    // =========================================================
    // 2. Buat rekap absensi per siswa
    // =========================================================
    $rekap = [];
    foreach ($siswaList as $siswa) {
        $rekap[$siswa->nis]['nama'] = $siswa->nama;

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $rekap[$siswa->nis]['bulan'][$bulan] = [
                'A' => Absensi::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->where('nis', $siswa->nis)
                    ->where('status', 'Alpha')
                    ->count(),
                'I' => Absensi::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->where('nis', $siswa->nis)
                    ->where('status', 'Izin')
                    ->count(),
                'S' => Absensi::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->where('nis', $siswa->nis)
                    ->where('status', 'Sakit')
                    ->count(),
                'T' => Absensi::whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $bulan)
                    ->where('nis', $siswa->nis)
                    ->where('status', 'Terlambat')
                    ->count(),
            ];
        }

        $rekap[$siswa->nis]['total'] = [
            'A' => array_sum(array_column($rekap[$siswa->nis]['bulan'], 'A')),
            'I' => array_sum(array_column($rekap[$siswa->nis]['bulan'], 'I')),
            'S' => array_sum(array_column($rekap[$siswa->nis]['bulan'], 'S')),
            'T' => array_sum(array_column($rekap[$siswa->nis]['bulan'], 'T')),
        ];
    }

    // =========================================================
// 3. Rekap izin per bulan
// =========================================================
$izin_kelas = [];

foreach ($siswaList as $siswa) {
    $izin_per_bulan = [];

    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $pribadi = IzinKelas::where('nis', $siswa->nis)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('keperluan', 'Pribadi')
            ->count();

        $sekolah = IzinKelas::where('nis', $siswa->nis)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->where('keperluan', 'Sekolah')
            ->count();

        $izin_per_bulan[$bulan] = [
            'pribadi' => $pribadi,
            'sekolah' => $sekolah,
            'total' => $pribadi + $sekolah,
        ];
    }

    $izin_kelas[] = [
        'nis' => $siswa->nis,
        'nama' => $siswa->nama,
        'izin_bulan' => $izin_per_bulan,
    ];
}

    // =========================================================
    // 4. Generate PDF
    // =========================================================
    $pdf = Pdf::loadView('pdf.rekap_for_walkes', [
        'kelas' => $kelas,
        'rekap' => $rekap,
        'izin_kelas' => $izin_kelas,
    ])->setPaper([0, 0, 936, 612]);

    return $pdf->stream('Rekap_Absensi_Siswa' . $kelas->kelas . '.pdf');
}




}
