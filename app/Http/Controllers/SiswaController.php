<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    //
    public function index(){
    // Ambil semua data siswa dari database
    $siswas = Siswa::orderBy('id','desc')->paginate(10);

    // Siapkan array untuk dikirim ke view
    $view_data = [
        'siswas' => $siswas,
        'title' => 'Data Siswa',
    ];

    // Kirim ke view
    return view('admin.data-master.data-siswa', $view_data);
    }

    //INPUT DATA
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswas',
            'nama' => 'required',
            'kelas' => 'required',
            'jk' => 'required',
            'wali_kelas' => 'required',
        ]);

        Siswa::create($request->all());

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }


    //EDIT DATA
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);

        $view_data = [
            'siswa' => $siswa,
            'title' => 'Edit Data Siswa',
        ];

        return view('admin.data-master.edit-data-siswa', $view_data);
    }



    
    public function update(Request $request, $id)
        {
            // Validasi input
            $request->validate([
                'nis' => 'required|unique:siswas,nis,' . $id, // biar NIS sendiri tidak dianggap duplicate
                'nama' => 'required',
                'jk' => 'required',
                'kelas' => 'required',
                'wali_kelas' => 'required',
                'jurusan' => 'nullable',
            ]);

            // Update data
            Siswa::where('id', $id)->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'jk' => $request->jk,
                'kelas' => $request->kelas,
                'wali_kelas' => $request->wali_kelas,

            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('admin.data-master.data-siswa')->with('success', 'Data berhasil diupdate.');
        }


    //HAPUS DATA
    public function destroy($id)
        {
           // Hapus data siswa langsung pakai where
            Siswa::where('id', $id)->delete();

            // Redirect kembali ke halaman data siswa dengan pesan sukses
            return redirect()->route('admin.data-master.data-siswa')->with('success', 'Data berhasil dihapus.');
        }

   // CARI DATA
        public function search(Request $request)
        {
            $search = $request->input('search'); // ambil input pencarian

            if ($search) {
                // Jika ada kata kunci pencarian
                $siswas = Siswa::where('nama', 'like', "%{$search}%")
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->appends(['search' => $search]); // simpan keyword saat pindah halaman
            } else {
                // Jika tidak ada pencarian, tampilkan semua dengan paginate juga
                $siswas = Siswa::orderBy('id', 'desc')->paginate(10);
            }

            return view('admin.data-master.cari-siswa', compact('siswas', 'search'));
        }
        }
