<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index()
    {
        $petugas = Petugas::all();
        return view('petugas.petugas.index', compact('petugas'));
    }

    public function cari(Request $request)
    {
        $keyword = $request->keyword;
        if (!$keyword) {
            return redirect('/petugas');
        }
        $petugas = Petugas::where('nama_petugas', 'LIKE', '%' . $keyword . '%')->get();
        return view('petugas.petugas.index', compact('petugas', 'keyword'));
    }

    public function tambah(Request $request)
    {
        $petugas = $request->validate([
            'username' => 'required|unique:petugas',
            'password' => 'required',
            'nama_petugas' => 'required',
            'level' => 'required',
        ]);

        $petugas['password'] = Hash::make($request->password);
        Petugas::create($petugas);

        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id)
    {
        $petugas_request = $request->validate([
            'username' => 'required',
            'nama_petugas' => 'required',
            'level' => 'required',
        ]);

        $petugas = Petugas::find($id);
        $petugas->update($petugas_request);

        if ($request->password) {
            $petugas->password = Hash::make($request->password);
            $petugas->save();
        }

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function hapus($id)
    {
        Petugas::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
