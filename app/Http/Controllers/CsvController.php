<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\DataKelas;

class CsvController extends Controller
{
    /**
     * Import data siswa dari siswa.csv
     */
    public function importSiswa(Request $request)
    {
        $request->validate([
            'csv_file_siswa' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file_siswa');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle);

            $expected = ['nis', 'nama', 'id_kelas', 'jk'];
            if (array_map('strtolower', $header) !== $expected) {
                return back()->with('error', 'Format CSV siswa salah! Gunakan header: nis,nama,id_kelas,jk');
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 4) continue;

                Siswa::updateOrCreate(
                    ['nis' => $row[0]],
                    [
                        'nama' => $row[1],
                        'id_kelas' => $row[2],
                        'jk' => $row[3],
                    ]
                );
            }

            fclose($handle);
        }

        return back()->with('success', 'Data siswa berhasil diimport!');
    }

    /**
     * Import data guru dari guru.csv
     */
    public function importGuru(Request $request)
    {
        $request->validate([
            'file_guru' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file_guru');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle);

            $expected = ['nip', 'nama_guru', 'mapel', 'no_hp'];
            if (array_map('strtolower', $header) !== $expected) {
                return back()->with('error', 'Format CSV guru salah! Gunakan header: nip,nama,mapel');
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3) continue;

                Guru::updateOrCreate(
                    ['nip' => $row[0]],
                    [
                        'nama_guru' => $row[1],
                        'mapel' => $row[2],
                        'no_hp' => $row[3],
                    ]
                );
            }

            fclose($handle);
        }

        return back()->with('success', 'Data guru berhasil diimport!');
    }

    /**
     * Import data kelas dari kelas.csv
     */
    public function importKelas(Request $request)
    {
        $request->validate([
            'file_data_kelas' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file_data_kelas');
        $path = $file->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle);

            $expected = ['id_wali_kelas', 'kode_kelas', 'kelas'];
            if (array_map('strtolower', $header) !== $expected) {
                return back()->with('error', 'Format CSV kelas salah! Gunakan header: id,kode_kelas,kelas');
            }


            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3) continue;

                \Log::info('Row CSV:', $row);


                DataKelas::updateOrCreate(
                    ['id_wali_kelas' => $row[0]],
                    [
                        'kode_kelas' => $row[1],
                        'kelas' => $row[2],
                    ]
                );
            }

            fclose($handle);
        }

        return back()->with('success', 'Data kelas berhasil diimport!');
    }
}
