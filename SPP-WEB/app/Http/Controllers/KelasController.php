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

    public function cari(Request $request)
    {
        $keyword = $request->keyword;
        if (!$keyword) {
            return redirect('/kelas');
        }
        $kelas = Kelas::where('nama_kelas', 'LIKE', '%' . $keyword . '%')->get();
        return view('petugas.kelas.index', compact('kelas', 'keyword'));
    }

    public function tambah(Request $request){
        $check = Kelas::where('nama_kelas', $request->nama_kelas)->where('kompetensi_keahlian', $request->kompetensi_keahlian)->first();
        if($check){
            return redirect()->back()->with('error', 'Kelas Tersebut Sudah Ada');
        }

        Kelas::create($request->validate([
            'nama_kelas' => 'required',
            'kompetensi_keahlian' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id){
        $check = Kelas::where('nama_kelas', $request->nama_kelas)->where('kompetensi_keahlian', $request->kompetensi_keahlian)->first();
        if($check && $id != $check->id_kelas){
            return redirect()->back()->with('error', 'Kelas Tersebut Sudah Ada');
        }

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
