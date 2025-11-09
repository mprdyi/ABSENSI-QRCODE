<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\DataKelas;
use App\Models\IzinKelas;
use App\Models\ProfilSekolah;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{

    public function index()
    {
        $hariIni = Carbon::today()->toDateString();;
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
        $totalHadir = $rekap['Hadir'] ?? 0;
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
        ->paginate(20);


        // === REKAP KELAS X ===
        $rekapKelasX = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'X-%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();

        // === REKAP KELAS XI ===
        $rekapKelasXI = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'XI-%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();



        // === REKAP KELAS XII ===
        $rekapKelasXII = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'XII%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();


        $tampil_kelas = DataKelas::orderByRaw("
        CASE
            WHEN kelas LIKE 'X %' THEN 1
            WHEN kelas LIKE 'XI %' THEN 2
            WHEN kelas LIKE 'XII %' THEN 3
            ELSE 4
            END, kelas ASC
        ")->get();

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
            'data_absensi' => $data_absensi,
            'tampil_kelas' => $tampil_kelas

        ];

        return view ('laporan.harian', $data);
    }




    //FILTER KELAS TAMPIL
    public function Filter(Request $request)
    {
        $hariIni = Carbon::today();
        $totalAbsensiHariIni = Absensi::whereDate('tanggal', $hariIni)->count();
        $tampil_kelas = DataKelas::orderBy('kelas', 'asc')->get();

        $absensi = Absensi::with('siswa.kelas.waliKelas')
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('jam_masuk', 'desc');

        //REKAP
        $rekap = Absensi::whereDate('tanggal', $hariIni)
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

        $totalSakit = $rekap['Sakit'] ?? 0;
        $totalHadir = $rekap['Hadir'] ?? 0;
        $totalTerlambat = $rekap['Terlambat'] ?? 0;
        $totalIzin = $rekap['Izin'] ?? 0;
        $totalAlpha = $rekap['Alpha'] ?? 0;


        if ($request->filled('kelas')) {
            $absensi->whereHas('siswa.kelas', function ($q) use ($request) {
                $q->where('kode_kelas', $request->kelas);
            });
        }
        $absensi = $absensi->paginate(30);


        // === REKAP KELAS X ===
        $rekapKelasX = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'X-%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();

        // === REKAP KELAS XI ===
        $rekapKelasXI = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'XI-%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();



        // === REKAP KELAS XII ===
        $rekapKelasXII = DB::table('data_absensi')
        ->join('siswas', 'data_absensi.nis', '=', 'siswas.nis')
        ->join('data_kelas', 'siswas.id_kelas', '=', 'data_kelas.kode_kelas')
        ->where('data_kelas.kelas', 'like', 'XII%')
        ->whereDate('data_absensi.tanggal', now()->toDateString())
        ->select(
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'hadir' THEN 1 ELSE 0 END) as total_hadir"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'izin' THEN 1 ELSE 0 END) as total_izin"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'sakit' THEN 1 ELSE 0 END) as total_sakit"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'alpha' THEN 1 ELSE 0 END) as total_alpha"),
            DB::raw("SUM(CASE WHEN LOWER(data_absensi.status) = 'terlambat' THEN 1 ELSE 0 END) as total_terlambat")
        )
        ->first();

        $view_data =[
            'absensi' => $absensi,
            'tampil_kelas' => $tampil_kelas,
            'totalAbsensiHariIni' => $totalAbsensiHariIni,
            'totalSakit' =>$totalSakit,
            'totalHadir' => $totalHadir,
            'totalTerlambat' => $totalTerlambat,
            'totalIzin' => $totalIzin,
            'totalAlpha' => $totalAlpha,
            'rekapKelasX' => $rekapKelasX,
            'rekapKelasXI' => $rekapKelasXI,
            'rekapKelasXII' => $rekapKelasXII
        ];

        return view('laporan.cari_data_kelas', $view_data);

    }



    // EDIT STATUS ABSENSI
    public function edit($id)
    {
        $absensi = Absensi::with('siswa.kelas.waliKelas')->findOrFail($id);

        $data = [
            'item_absen' => $absensi
        ];
        return view('absensi-siswa.data-absensi.edit-absensi', $data);
    }


    //UPDATE ABSENSI
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha,Terlambat',
        ]);

        $absen = Absensi::findOrFail($id);
         // Ambil jam masuk dari tabel profile_sekolah
         $profil = ProfilSekolah::first();
         $jamMasuk = Carbon::createFromFormat('H:i:s', $profil->jam_masuk);

        // Tambah toleransi keterlambatan, misal 5 menit
        $batasTerlambat = $jamMasuk;
        $jamSekarang = Carbon::now();



        $absen->status = $validated['status'];
        if ($validated['status'] !== 'Terlambat') {
            $absen->keterangan = '0 menit';
        }else {
            $menitTerlambat = $jamSekarang->diffInMinutes($batasTerlambat);
            $keterangan = "{$menitTerlambat} menit";
            $absen->keterangan = $keterangan;
        }

        $absen->save();

        return redirect()->route('laporan-harian.harian')
                         ->with('success', 'Data absensi berhasil diperbarui.');
    }


    public function DownloadBackup()
    {
    $hariIni = Carbon::today();

    // ===  REKAP TOTAL PER STATUS ===
    $rekap = Absensi::whereDate('tanggal', $hariIni)
        ->select('status')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    $totalHadir     = $rekap['Hadir'] ?? 0;
    $totalIzin      = $rekap['Izin'] ?? 0;
    $totalSakit     = $rekap['Sakit'] ?? 0;
    $totalAlpha     = $rekap['Alpha'] ?? 0;
    $totalTerlambat = $rekap['Terlambat'] ?? 0;

    $totalHadir =    $totalHadir +  $totalTerlambat;

    // === 2️⃣ DATA SISWA TERLAMBAT ===
    $dataTerlambat = Absensi::with('siswa.kelas')
        ->whereDate('tanggal', $hariIni)
        ->where('status', 'Terlambat')
        ->get()
        ->sortBy(function ($absen) {
            $kelas = $absen->siswa->kelas->kelas ?? '';
            if (str_starts_with($kelas, 'XII')) $level = 3;
            elseif (str_starts_with($kelas, 'XI')) $level = 2;
            else $level = 1;

            preg_match('/[A-Z0-9]+$/', $kelas, $huruf);
            $subkelas = $huruf[0] ?? '';
            $nama = strtoupper($absen->siswa->nama ?? 'ZZZ');
            return sprintf('%02d-%s-%s', $level, $subkelas, $nama);
        });

    // === 3️⃣ DATA KETIDAKHADIRAN (IZIN, SAKIT, ALPA) ===
    $dataKetidakhadiran = Absensi::with('siswa.kelas')
        ->whereDate('tanggal', $hariIni)
        ->whereIn('status', ['Izin', 'Sakit', 'Alpha'])
        ->get()
        ->sortBy(function ($absen) {
            $kelas = $absen->siswa->kelas->kelas ?? '';
            if (str_starts_with($kelas, 'XII')) $level = 3;
            elseif (str_starts_with($kelas, 'XI')) $level = 2;
            else $level = 1;

            preg_match('/[A-Z0-9]+$/', $kelas, $huruf);
            $subkelas = $huruf[0] ?? '';
            $nama = strtoupper($absen->siswa->nama ?? 'ZZZ');
            return sprintf('%02d-%s-%s', $level, $subkelas, $nama);
        });

    $hariTeks = $hariIni->translatedFormat('d F Y');
    $jamCetak = Carbon::now()->translatedFormat('d F Y H:i');

    $pdf = Pdf::loadView('laporan.rekap_ketidakhadiran', [
        'hariTeks' => $hariTeks,
        'jamCetak' => $jamCetak,
        'totalHadir' => $totalHadir,
        'totalIzin' => $totalIzin,
        'totalSakit' => $totalSakit,
        'totalAlpha' => $totalAlpha,
        'totalTerlambat' => $totalTerlambat,
        'dataTerlambat' => $dataTerlambat,
        'dataKetidakhadiran' => $dataKetidakhadiran,
    ])->setPaper([0, 0, 595.276, 935.433], 'portrait');

    return $pdf->download('Rekap_Absensi_' . $hariIni->format('d-m-Y') . '.pdf');
    }




    public function arsip(){

    $absensi = Absensi::with('siswa.kelas.waliKelas')
    ->orderBy('tanggal', 'desc')
    ->orderBy('jam_masuk', 'desc')
    ->paginate(20);

     //ambil semua kelas
     $kelas = DataKelas::all();

    $view_data = [
        'data_absensi' =>   $absensi,
        'kelas' => $kelas,
    ];
        return view('laporan.arsip',$view_data );
    }

    //GET WALI KELAS
    public function getWaliKelas($id)
    {
        $kelas = DataKelas::with('waliKelas')->where('kode_kelas', $id)->first();

        if ($kelas && $kelas->waliKelas) {
            return response()->json([
                'wali' => [
                    'id' => $kelas->waliKelas->id,
                    'nama' => $kelas->waliKelas->nama_guru
                ]
            ]);
        } else {
            return response()->json(['wali' => null]);
        }
    }


    public function downloadRekapPDF(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required',
        ]);

        $kelas = DataKelas::with(['waliKelas', 'siswa'])->where('kode_kelas', $request->id_kelas)->first();

        if (!$kelas) {
            return back()->with('error', 'Kelas tidak ditemukan.');
        }

        $tahun = now()->year;
        $siswaList = $kelas->siswa;

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

         //  Ambil data izin dan hitung kategori
         $izin_kelas = [];
         foreach ($siswaList as $siswa) {
             $izin_pribadi = IzinKelas::where('nis', $siswa->nis)
                 ->whereYear('created_at', $tahun)
                 ->where('keperluan', 'Pribadi')
                 ->count();

             $izin_sekolah = IzinKelas::where('nis', $siswa->nis)
                 ->whereYear('created_at', $tahun)
                 ->where('keperluan', 'Sekolah')
                 ->count();

             $izin_kelas[] = [
                 'nis' => $siswa->nis,
                 'nama' => $siswa->nama,
                 'jk' => $siswa->jk,
                 'kelas' => $kelas->kelas,
                 'pribadi' => $izin_pribadi,
                 'sekolah' => $izin_sekolah,
                 'total' => $izin_pribadi + $izin_sekolah,
             ];
            }
        $pdf = Pdf::loadView('pdf.rekap_tahunan', [
            'kelas' => $kelas,
            'rekap' => $rekap,
            'izin_kelas' =>  $izin_kelas
        ])->setPaper([0, 0, 936, 612])->output();

        $pdf2 = Pdf::loadView('pdf.izin_kelas', [
            'kelas' => $kelas,
            'izin_kelas' => $izin_kelas,
        ])->setPaper([0, 0, 612, 936], 'portrait')->output();

        // === Simpan sementara dan zip ===
        Storage::put('Rekap_Tahunan.pdf', $pdf);
        Storage::put('Rekap_Izin.pdf', $pdf2);

        $zip = new ZipArchive;
        $zipFileName = 'Rekap_Absensi_' . $kelas->kelas . '.zip';
        $zipPath = storage_path("app/$zipFileName");

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFile(storage_path('app/Rekap_Tahunan.pdf'), 'Rekap_Tahunan.pdf');
            $zip->addFile(storage_path('app/Rekap_Izin.pdf'), 'Rekap_Izin.pdf');
            $zip->close();
        }

        // Bersihkan file PDF sementara
        Storage::delete(['Rekap_Tahunan.pdf', 'Rekap_Izin.pdf']);

        // === Kirim file ZIP ===
        return response()->download($zipPath)->deleteFileAfterSend(true);


        //return $pdf->stream('Rekap_Absensi_Tahunan_' . $kelas->kelas . '.pdf');
    }




    //  CARI ASRIP
    public function CariArsip(Request $request)
    {
    $search = $request->input('search');

    $absensi = Absensi::with('siswa.kelas.waliKelas')
        ->when($search, function ($query, $search) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhereHas('kelas', function ($q2) use ($search) {
                      $q2->where('kelas', 'like', "%{$search}%");
                  });
            });
        })
        ->orderBy('tanggal', 'desc')
        ->orderBy('jam_masuk', 'desc')
        ->paginate(20);

        $view_data = [
            'data_absensi' =>   $absensi
        ];

    return view('laporan.cari-arsip', $view_data);
    }



}
