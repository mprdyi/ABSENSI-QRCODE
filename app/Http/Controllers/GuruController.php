<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{

    // MENAMPILKAN LIST DATA
    public function index()
    {
        $data_guru = Guru::orderBy('id', 'desc')->paginate(10);
        $totalGuru = Guru::count();

        $view_data = [
            'guru' => $data_guru,
            'title'=> 'Data Guru',
            'totalguru' =>  $totalGuru,
        ];
        return view('admin.data-master.data-guru', $view_data);

    }


    // INSERT DATA
    public function store(Request $request)
    {

        $request->validate([
            'nip' => 'required|unique:gurus',
            'nama_guru' => 'required',
            'mapel' => 'required',
            'no_hp' => 'required',
        ]);

       Guru::create($request->only(['nip','nama_guru','mapel','no_hp']));

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan!');
    }


    // MENAMPILKAN DATA FORM EDIT
    public function edit($id)
    {
        $data_guru = Guru::findOrFail($id);

        $view_data = [
            'Guru' => $data_guru,
            'title' => 'Edit Data Guru',
        ];

        return view('admin.data-master.edit-data-guru', $view_data);
    }


    // UPDATE DATA
    public function update(Request $request,$id)
    {
         $request->validate([
            'nip' => 'required|unique:gurus,nip,' . $id,
            'nama_guru' => 'required',
            'mapel' => 'required',
            'no_hp' => 'required',
        ]);

        Guru::where('id', $id)->update([
            'nip' => $request->nip,
            'nama_guru' => $request->nama_guru,
            'mapel' => $request->mapel,
            'no_hp' => $request->no_hp,
        ]);
        return redirect()->route('admin.data-master.data-guru')->with('success', 'Data berhasil diupdate.');
    }


    // HAPUS DATA
    public function destroy($id)
    {
        Guru::where('id', $id)->delete();
        return redirect()->route('admin.data-master.data-guru')->with('success', 'Data berhasil dihapus.');

    }

     // CARI DATA GURU
     public function search(Request $request)
     {
         $search = $request->input('search'); // ambil input pencarian

         if ($search) {
             // Jika ada kata kunci pencarian
             $guru = Guru::where('nama_guru', 'like', "%{$search}%")
                 ->orderBy('id', 'desc')
                 ->paginate(10)
                 ->appends(['search' => $search]); // simpan keyword saat pindah halaman
         } else {
             // Jika tidak ada pencarian, tampilkan semua dengan paginate juga
             $guru = Guru::orderBy('id', 'desc')->paginate(10);
         }
         return view('admin.data-master.cari-guru', compact('guru', 'search'));
        }




}
