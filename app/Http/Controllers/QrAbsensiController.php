<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Siswa;

class QrAbsensiController extends Controller
{


    public function index()
    {
        
        $totalSiswa = Siswa::count();
        $totalAbsensiHariIni = Absensi::count();

        $V_data = [
          'total_siswa' => $totalSiswa,
          'total_Absensi_Hari_ini' => $totalAbsensiHariIni,
        ];
        return view('absensi-siswa.data-absensi.data-absensi', $V_data);
    }
    
         

    //INSERT DATA
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric',
        ]);

        // ✅ Cek apakah NIS ada di tabel siswa
        $siswa = Siswa::where('nis', $request->nis)->first();
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIS tidak ditemukan di database siswa.'
            ], 404); // 404 = Not Found
        }


        // Cek apakah sudah pernah absen hari ini
        $sudahAbsen = Absensi::where('nis', $request->nis)
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

    public function dataAbsensiQr()
    {
        $absensi = \App\Models\Absensi::orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->limit(5)
            ->get();

        $html = '';
        $no = 1;

        foreach ($absensi as $a) {

            $badgeClass = '';
        switch (strtolower($a->status)) {
            case 'hadir':
                $badgeClass = 'badge-soft green';
                break;
            case 'terlambat':
                $badgeClass = 'badge-soft orange';
                break;
            case 'alpha':
                $badgeClass = 'badge-soft red';
                break;
            default:
                $badgeClass = 'badge-soft blue'; 
                break;
              }

            $html .= '<tr>
                <td>'.$no++.'</td>
                <td>'.$a->nis.'</td>
                <td>'.$a->id_kelas.'</td>
                <td>'.$a->id_wali_kelas.'</td>
                <td><span class="'.$badgeClass.'">'.$a->status.'</span></td>
            </tr>';
        }

        return response()->json(['html' => $html]);
    }



    
        public function show(string $id)
        {
            //
        }

    
        public function edit(string $id)
        {
            //
        }

        
        public function update(Request $request, string $id)
        {
            //
        }

    
        public function destroy(string $id)
        {
            //
        }
    }
