<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DataKelas;
use App\Models\IzinKelas;
use App\Models\ProfilSekolah;
use Barryvdh\DomPDF\Facade\Pdf;


class IzinKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();
    $izin = IzinKelas::with('siswa.kelas.waliKelas')
    ->orderBy('created_at', 'desc')
    ->paginate(10);

    $hitung_data = IzinKelas::count();

    return view('izin-meninggalkan-kelas.izin-kelas', compact('data_kelas', 'izin', 'hitung_data'));
    }

    // Ambil semua siswa berdasarkan kelas (AJAX)
    public function getSiswaByKelas($id_kelas)
    {
        $siswas = Siswa::where('id_kelas', $id_kelas)
        ->orderBy('nama', 'asc')
        ->get(['nis', 'nama']);

        return response()->json($siswas);
    }


    //INSERRT
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required',
            'kode_kelas' => 'required',
            'jam_izin' => 'required',
            'jam_expired' => 'required',
            'keperluan' => 'nullable|string',
        ]);

        // Gabungkan keperluan sekolah/pribadi jika dicentang
        $keperluanList = [];
        if ($request->has('sekolah')) $keperluanList[] = 'Sekolah';
        if ($request->has('pribadi')) $keperluanList[] = 'Pribadi';

        $gabunganKeperluan = implode(', ', $keperluanList);
        if ($request->keperluan) {
            $gabunganKeperluan .= ($gabunganKeperluan ? ', ' : '') . $request->keperluan;
        }
        IzinKelas::create([
            'nis' => $request->nis,
            'kode_kelas' => $request->kode_kelas,
            'waktu_izin' => $request->jam_izin,
            'waktu_habis' => $request->jam_expired,
            'keperluan' => $gabunganKeperluan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data izin berhasil disimpan!');
    }


    public function DownloadSurat(string $nis)
    {
      // Ambil data izin berdasarkan NIS
    $izin = IzinKelas::where('nis', $nis)->latest()->first();

    if (!$izin) {
        return redirect()->back()->with('error', 'Data izin tidak ditemukan.');
    }

    // Ambil data siswa dan relasi
    $siswa = Siswa::with('kelas.waliKelas')->where('nis', $nis)->first();

    // Kirim data ke view PDF
    $pdf = Pdf::loadView('laporan.surat-izin-keluar-kelas', compact('izin', 'siswa'))
    ->setPaper('A6', 'landscape');;

    $namaFile = 'Surat_Izin_' . $siswa->nama . '.pdf';
    return $pdf->download($namaFile);
}

   // Fungsi pencarian
   public function cari(Request $request)
   {
       $keyword = $request->get('search');

       // cari berdasarkan nama siswa
       $izin = IzinKelas::whereHas('siswa', function ($query) use ($keyword) {
               $query->where('nama', 'LIKE', "%{$keyword}%");
           })
           ->with('siswa.kelas.waliKelas')
           ->orderBy('created_at', 'desc')
           ->paginate(10);

       $hitung_data = $izin->total(); // hitung hasil pencarian

       // kirim kembali ke view dengan kata kunci
       return view('laporan.cari_izin', compact('izin', 'hitung_data', 'keyword'));
   }

}

