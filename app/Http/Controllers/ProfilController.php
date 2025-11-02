<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilSekolah;

class ProfilController extends Controller
{

    public function boot()
{
    // Data profil sekolah tersedia di semua view
    View::composer('*', function ($view) {
        $view->with('sekolah', ProfilSekolah::first());
    });
}

    public function index()
    {
        $sekolah = ProfilSekolah::first(); // ambil record pertama
        return view('profile-sekolah', compact('sekolah'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id'           => 'required|integer',
            'nama_sekolah' => 'required|string|max:255',
            'kepsek'       => 'required|string|max:255',
            'jam_masuk'    => 'required|date_format:H:i:s',
        ]);

        $sekolah = ProfilSekolah::findOrFail($validated['id']);

        // paksa update semua kolom
        $sekolah->forceFill([
            'nama_sekolah' => $validated['nama_sekolah'],
            'kepsek'       => $validated['kepsek'],
            'jam_masuk'    => $validated['jam_masuk'],
        ])->save();

        return redirect()->back()->with('success', 'Profil sekolah berhasil diperbarui!');
    }
}
