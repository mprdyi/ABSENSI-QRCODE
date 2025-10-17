<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data_guru = Guru::orderBy('id', 'desc')->get();

        $view_data = [
            'guru' => $data_guru,
            'title'=> 'Data Guru'
        ];
        return view('admin.data-master.data-guru', $view_data);

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

        $request->validate([
            'nip' => 'required|unique:gurus',
            'nama_guru' => 'required',
            'mapel' => 'required',
            'no_hp' => 'required',
        ]);

       Guru::create($request->only(['nip','nama_guru','mapel','no_hp']));

        return redirect()->back()->with('success', 'Data Guru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Guru::where('id', $id)->delete();
        return redirect()->route('admin.data-master.data-guru')->with('success', 'Data berhasil dihapus.');

    }
}
