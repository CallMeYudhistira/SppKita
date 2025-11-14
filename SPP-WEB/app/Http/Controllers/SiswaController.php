<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(){
        $kelas = Siswa::all();
        return view('petugas.kelas.index', compact('kelas'));
    }

    public function tambah(Request $request){
        Siswa::create($request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id){
        Siswa::find($id)->update($request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function hapus($id){
        Siswa::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
