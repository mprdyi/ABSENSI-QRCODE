<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absensi;

class QrAbsensiController extends Controller
{


    public function index()
    {
        $absensi = Absensi::orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();

        // Render view partial menjadi string HTML
        $html = view('absensi-siswa.data-absensi.data-absensi', compact('absensi'))->render();

        //return response()->json(['html' => $html]);
        return view('absensi-siswa.data-absensi.data-absensi');
    }
    
         

    /*
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
    $request->validate([
        'nis' => 'required|numeric',
    ]);

    // Cek apakah sudah pernah absen hari ini
    $sudahAbsen = \App\Models\Absensi::where('nis', $request->nis)
        ->whereDate('tanggal', now()->toDateString())
        ->exists();

    if ($sudahAbsen) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa ini sudah absen hari ini!'
        ], 409); // 409 = Conflict
    }

    $jamSekarang = Carbon::now();
    $batasTerlambat = Carbon::createFromTime(6, 35, 0); // jam 06:35:00
    $status = $jamSekarang->greaterThan($batasTerlambat) ? 'Terlambat' : 'Hadir';

    // Kalau belum, simpan data absensi
    $absen = \App\Models\Absensi::create([
        'nis' => $request->nis,
        'id_kelas' => 1,
        'id_wali_kelas' => 1,
        'tanggal' => now()->format('Y-m-d'),
        'jam_masuk' => now()->format('H:i:s'),
        'status' => $status,
        'keterangan' => '-',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Absensi berhasil disimpan!',
        'data' => $absen
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
