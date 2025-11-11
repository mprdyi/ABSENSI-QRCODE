<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
       return view ('profile-user', compact('user'));
    }


    // EDIT
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email'     => 'nullable|string|max:255',
            'password'  => 'nullable|min:6|confirmed'

        ]);

        // Update data
        $user->name  = $request->nama_user;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }


}
