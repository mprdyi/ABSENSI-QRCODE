<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomRessetPasswordController extends Controller
{
    // Menampilkan form reset password
    public function showResetForm(Request $request, $token)
    {
        return view('resset-pw', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    // Proses reset password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );


        if ($status === Password::PASSWORD_RESET) {
            return back()->with('status', 'Password berhasil direset. Silakan login.');
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
}
