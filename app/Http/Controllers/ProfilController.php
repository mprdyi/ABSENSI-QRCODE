<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilSekolah;

class ProfilController extends Controller
{


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
            'auto_alpa'    => 'required|date_format:H:i:s',
            'notif_wa'    => 'required|date_format:H:i:s',
        ]);

        $sekolah = ProfilSekolah::findOrFail($validated['id']);

        // paksa update semua kolom
        $sekolah->forceFill([
            'nama_sekolah' => $validated['nama_sekolah'],
            'kepsek'       => $validated['kepsek'],
            'jam_masuk'    => $validated['jam_masuk'],
            'auto_alpa'    => $validated['auto_alpa'],
            'notif_wa'    => $validated['notif_wa'],
        ])->save();

        return redirect()->back()->with('success', 'Profil sekolah berhasil diperbarui!');
    }
}
