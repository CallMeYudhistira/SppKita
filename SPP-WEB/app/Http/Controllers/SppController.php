<?php

namespace App\Http\Controllers;

use App\Models\Spp;
use Illuminate\Http\Request;

class SppController extends Controller
{
    public function index()
    {
        $spp = Spp::all();
        return view('petugas.spp.index', compact('spp'));
    }

    public function cari(Request $request)
    {
        $keyword = $request->keyword;
        if (!$keyword) {
            return redirect('/spp');
        }
        $spp = Spp::where('tahun', 'LIKE', '%' . $keyword . '%')->get();
        return view('petugas.spp.index', compact('spp', 'keyword'));
    }

    public function tambah(Request $request)
    {
        $check = Spp::where('tahun', $request->tahun)->first();
        if($check){
            return redirect()->back()->with('error', 'Tahun Tersebut Sudah Memiliki Nominal!');
        }

        Spp::create($request->validate([
            'tahun' => 'required',
            'nominal' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id)
    {
        $check = Spp::where('tahun', $request->tahun)->first();
        if($check && $id != $check->id_spp){
            return redirect()->back()->with('error', 'Tahun Tersebut Sudah Memiliki Nominal!');
        }

        Spp::find($id)->update($request->validate([
            'tahun' => 'required',
            'nominal' => 'required',
        ]));
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function hapus($id)
    {
        Spp::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
