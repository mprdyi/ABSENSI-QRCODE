<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FaceController extends Controller
{
    // ===================
    // REGESTRASI FACE ID
    // ===================
    public function registerPage($nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();
        if (!$siswa) abort(404, 'Siswa tidak ditemukan');

        return view('face_register.face_siswa', compact('siswa'));
    }


    //========================
    // SIMPAN REGISTRASI
    //=========================
    public function saveFace(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'face_descriptor' => 'required|array'
        ]);

        Siswa::where('nis', $request->nis)->update([
            'face_descriptor' => json_encode($request->face_descriptor)
        ]);

        return response()->json(['success' => true]);
    }


    // =========================
    // TAMPILKAN HALAMAN ABSENSI
    //==========================
    public function absensiPage()
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        $hariIni = Carbon::today()->toDateString();

        if (!$siswa) {
            return "Data Siswa tidak ditemukan. Pastikan NIS di kolom email user cocok dengan tabel siswa.";
        }
        if (!$siswa->face_descriptor) {
            return "Wajah belum terdaftar. Silakan registrasi wajah terlebih dahulu.";
        }

        //untuk tampilan
        $statusHadir = Absensi::where('nis', $siswa->nis)
        ->where('tanggal', $hariIni)
        ->first();
        //$total_siswa = Siswa::count();
        //$total_absensi = Absensi::count();
        $total_absensi_saya = Absensi::where('nis', $siswa->nis)->count();

        $total_hadir_saya = Absensi::where('nis', $siswa->nis)
            ->where('status', 'Hadir')
            ->count();

        $total_terlambat_saya = Absensi::where('nis', $siswa->nis)
            ->where('status', 'Terlambat')
            ->count();
         $total_sakit_saya = Absensi::where('nis', $siswa->nis)
            ->where('status', 'Sakit')
            ->count();
         $total_alpa_saya = Absensi::where('nis', $siswa->nis)
            ->where('status', 'Alpha')
            ->count();
         $total_izin_saya = Absensi::where('nis', $siswa->nis)
            ->where('status', 'Izin')
            ->count();

        $riwayatAbsensi = Absensi::where('nis', $siswa->nis)
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_masuk', 'desc')
        ->limit(10)
        ->get();

        $data_view = [
            'siswa' => $siswa,
            'face_descriptor' => $siswa->face_descriptor,
            'total_absensi_saya' => $total_absensi_saya,
            'total_hadir_saya' => $total_hadir_saya,
            'total_alpa_saya' => $total_alpa_saya,
            'total_sakit_saya' => $total_sakit_saya,
            'total_izin_saya' => $total_izin_saya,
            'total_terlambat_saya' => $total_terlambat_saya,
            'statusHadir' => $statusHadir,
            'riwayat_absensi' => $riwayatAbsensi
        ];

        return view('face_register.face-id', $data_view);
    }



    // ================================
    // ABSENSI MASUK KE DB
    // ===================================
    public function storeAbsensi(Request $request)
{
    $request->validate(['nis' => 'required']);
    $hariIni = Carbon::today()->toDateString();

    $sudahAbsen = Absensi::where('nis', $request->nis)
                         ->where('tanggal', $hariIni)
                         ->first();

    if ($sudahAbsen) {
        return response()->json([
            'success' => false,
            'message' => 'Kamu sudah absen hari ini jam ' . $sudahAbsen->jam_masuk
        ], 422);
    }

    $sekarang = Carbon::now();
    $jamSetting = session('jam_masuk', '07:00:00');
    $waktuBatasMasuk = Carbon::createFromTimeString($jamSetting);

    if ($sekarang->gt($waktuBatasMasuk)) {
        $status = "Terlambat";
        $menitTerlambat = $sekarang->diffInMinutes($waktuBatasMasuk);
        $keterangan = "Terlambat ". $menitTerlambat . " Menit";
    }
    else {
        $status = "Hadir";
        $keterangan = "Ontime";
    }

    Absensi::create([
        'nis'        => $request->nis,
        'tanggal'    => $sekarang->toDateString(),
        'jam_masuk'  => $sekarang->toTimeString(),
        'status'     => $status,
        'keterangan' => $keterangan,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Absen berhasil dicatat: ' . $keterangan
    ]);
}
}
