<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DataKelas;


class IzinKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    $data_kelas = DataKelas::orderBy('kelas', 'asc')->get();
    return view('izin-meninggalkan-kelas.izin-kelas', compact('data_kelas'));
    }

    // Ambil semua siswa berdasarkan kelas (AJAX)
    public function getSiswaByKelas($id_kelas)
    {
        $siswas = Siswa::where('id_kelas', $id_kelas)
        ->orderBy('nama', 'asc')
        ->get(['nis', 'nama']);
        
        return response()->json($siswas);
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
        //
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
