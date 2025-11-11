<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){

        $data = User::orderBy('id', 'asc')->paginate(10);
        $view_data = [
            'data' =>$data
        ];
        return view('admin.data-master.data-user', $view_data);
    }

    //  Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|unique:users,email',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan!');
    }

    //  Menampilkan form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.data-master.edit-user', compact('user'));
    }

    //  Update data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|unique:users,email,' . $user->id,
            'role'      => 'required',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.data')->with('success', 'Data user berhasil diperbarui!');
    }

    //  Hapus data user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
}
