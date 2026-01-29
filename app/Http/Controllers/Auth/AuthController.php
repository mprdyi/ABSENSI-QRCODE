<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);


            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // --- BAGIAN PENYIMPANAN SESSION (TAMBAHKAN INI) ---
                $sekolah = DB::table('profile_sekolah')->first();

                if ($sekolah) {
                    session([
                        'nama_sekolah'      => $sekolah->nama_sekolah,
                        'jam_masuk' => $sekolah->jam_masuk,
                        'auto_alpa'         => $sekolah->auto_alpa,
                    ]);
                }

            // arahkan ke satu halaman dashboard
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
