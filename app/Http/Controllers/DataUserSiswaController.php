<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\DataKelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;

class DataUserSiswaController extends Controller
{
    public function dataSiswa()
    {
        $today = Carbon::today(); // tanggal hari ini
        $nis = Auth::user()->email;

        $siswaLogin = Siswa::with('kelas')->where('nis', $nis)->firstOrFail();

        // Ambil semua siswa di kelas sama beserta absensi hari ini
        $siswas = Siswa::with(['user', 'kelas', 'absensi' => function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        }])
        ->where('id_kelas', $siswaLogin->id_kelas)
        ->get();

        // Prioritaskan yang izin, sakit, terlambat
        $priorityOrder = ['alpha', 'izin', 'sakit', 'terlambat']; // urutan prioritas
        $siswas = $siswas->sortBy(function($siswa) use ($priorityOrder) {
            $status = $siswa->absensi->first()?->status ?? 'hadir';
            $index = array_search(strtolower($status), $priorityOrder);
            return $index === false ? count($priorityOrder) : $index;
        });

        return view('km.data-absen-kelas', compact('siswas', 'siswaLogin'));
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

    // 1️⃣ Cek apakah siswa ada
    $siswa = Siswa::where('nis', $nis)->first();
    if (!$siswa) {
        return redirect()->back()->withErrors(['siswa_ajukan' => 'Siswa tidak ditemukan.']);
    }

    // 2️⃣ Cek apakah hari ini sudah ada absensi "hadir" (patokan hari aktif)
    $adaHadirHariIni = Absensi::whereDate('tanggal', now()->toDateString())
        ->where('status', 'hadir')
        ->exists();

    if (!$adaHadirHariIni) {
        return redirect()->back()->withErrors([
            'status_ajukan' => 'Belum ada siswa yang hadir hari ini, sepertinya hari ini libur. Pengajuan izin/sakit tidak bisa dilakukan.'
        ]);
    }

    // 3️⃣ Cek apakah siswa sudah absen hari ini
    $sudahAbsen = Absensi::where('nis', $nis)
        ->whereDate('tanggal', now()->toDateString())
        ->exists();

    if ($sudahAbsen) {
        return redirect()->back()->withErrors([
            'siswa_ajukan' => 'Siswa ini sudah memiliki data absensi hari ini.'
        ]);
    }

    // 4️⃣ Simpan data absensi izin/sakit
    Absensi::create([
        'nis' => $nis,
        'tanggal' => now()->format('Y-m-d'),
        'jam_masuk' => now()->format('H:i:s'),
        'status' => $request->status_ajukan,
        'keterangan' => $request->keterangan_ajukan,
    ]);

    return redirect()->back()->with('status', 'Absensi berhasil diajukan!');
}



}
