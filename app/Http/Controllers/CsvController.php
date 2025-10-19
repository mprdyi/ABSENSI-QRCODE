<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class CsvController extends Controller
{
    /**
     * Import CSV ke database
     */
    public function import(Request $request)
    {
        // validasi file CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $file_path = $file->getRealPath();

        // buka file CSV
        if (($file_handle = fopen($file_path, 'r')) !== false) {

            $header = fgetcsv($file_handle); // ambil header CSV

            // optional: cek header sesuai format
            $expected = ['nis','nama','id_kelas','jk'];
            $header_lower = array_map('strtolower', $header);

            if ($header_lower !== $expected) {
                return back()->with('error', 'Format CSV tidak sesuai. Gunakan kolom: nis,nama,id_kelas,jk');
            }

            // loop setiap row CSV
            while (($row = fgetcsv($file_handle)) !== false) {
                // skip row kosong
                if (count($row) < 4) continue;

                // pakai updateOrCreate supaya nis unik
                Siswa::updateOrCreate(
                    ['nis' => $row[0]], // nis sebagai key unik
                    [
                        'nama' => $row[1],
                        'id_kelas' => $row[2],
                        'jk' => $row[3],
                    ]
                );
            }

            fclose($file_handle);
        } else {
            return back()->with('error', 'File CSV gagal dibuka.');
        }

        return back()->with('success', 'CSV berhasil diimport!');
    }
}
