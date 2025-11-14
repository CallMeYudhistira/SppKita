<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(){
        $kelas = Kelas::all();
        return view('petugas.kelas.index', compact('kelas'));
    }

    public function tambah(Request $request){
        Kelas::create($request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id){
        Kelas::find($id)->update($request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function hapus($id){
        Kelas::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
