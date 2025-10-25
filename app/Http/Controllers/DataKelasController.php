<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKelas;
use App\Models\Guru;

class DataKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data_guru = Guru::orderBy('nama_guru', 'asc')->get();

        // Ambil data kelas + relasi wali kelas-nya
        $kelas = DataKelas::with('waliKelas')->orderBy('id', 'desc')->paginate(10);


        $view_data = [
            'kelas' => $kelas,
            'title' => 'Data Kelas',
            'guru' => $data_guru,
        ];
        

        return view('admin.data-master.data-kelas', $view_data);
    }


    /**
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

        try {
            // Validasi input
            $request->validate([
                'id_wali_kelas' => 'required|unique:data_kelas',
                'kode_kelas' => 'required|unique:data_kelas',
                'kelas' => 'required',
            ]);

            // Simpan data ke database
            DataKelas::create($request->all());

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Satu wali kelas untuk  satu kelas, periksa kembali input Anda.');
        } catch (\Exception $e) {
            // Kalau error lain (misal koneksi DB, atau duplikat, dsb)
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show(string $id)
    {
        //
    }



    public function edit(string $id)
    {
        $kelas = DataKelas::findOrFail($id);
        $guru = Guru::orderBy('nama_guru', 'asc')->get();

        $view_data = [
            'kelas' => $kelas,
            'guru' => $guru,
            'title' => 'Edit Data Kelas',
        ];

        return view('admin.data-master.edit-data-kelas', $view_data);

    }


    public function update(Request $request, string $id)
    {
        try {
            // Validasi input
            $request->validate([
                'kode_kelas' => 'required|unique:data_kelas,kode_kelas,' . $id,
                'kelas' => 'required',
                'id_wali_kelas' => 'required|unique:data_kelas,id_wali_kelas,' . $id,
            ]);

            // Update data
            DataKelas::where('id', $id)->update([
                'kode_kelas' => $request->kode_kelas,
                'kelas' => $request->kelas,
                'id_wali_kelas' => $request->id_wali_kelas,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('admin.data-master.data-kelas')->with('success', 'Data kelas berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data! Periksa input kamu.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', ' Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {

        DataKelas::where('id', $id)->delete();
        return redirect()->route('admin.data-master.data-kelas')->with('success', 'Data berhasil dihapus.');

    }


    // CARI DATA
    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $kelas = DataKelas::with('waliKelas')
                ->where('kelas', 'like', "%{$search}%")
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->appends(['search' => $search]);
        } else {
            // Jika tidak ada pencarian, tampilkan semua dengan relasi juga
            $kelas = DataKelas::with('waliKelas')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }


        return view('admin.data-master.cari-kelas', compact('kelas', 'search'));
    }
}
