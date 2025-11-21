<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Petugas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginProses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $petugas = Petugas::where('username', $request->username)->first();
        $siswa = Siswa::where('username', $request->username)->first();

        if ($petugas && Hash::check($request->password, $petugas->password)) {
            Auth::guard('petugas')->login($petugas);
            $request->session()->regenerate();

            Log::create([
                'aktifitas' => 'Melakukan login',
                'id_petugas' => Auth::guard('petugas')->user()->id_petugas,
            ]);

            return redirect('petugas/beranda');
        }

        if ($siswa && Hash::check($request->password, $siswa->password)) {
            Auth::guard('siswa')->login($siswa);
            $request->session()->regenerate();

            Log::create([
                'aktifitas' => 'Melakukan login',
                'nisn' => Auth::guard('siswa')->user()->nisn,
            ]);

            return redirect('siswa/beranda');
        }

        return redirect('/login')->with('error', 'Username atau Password Salah!');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('petugas')->check()) {
            Log::create([
                'aktifitas' => 'Melakukan logout',
                'id_petugas' => Auth::guard('petugas')->user()->id_petugas,
            ]);

            Auth::guard('petugas')->logout();
        }

        if (Auth::guard('siswa')->check()) {
            Log::create([
                'aktifitas' => 'Melakukan logout',
                'nisn' => Auth::guard('siswa')->user()->nisn,
            ]);

            Auth::guard('siswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
