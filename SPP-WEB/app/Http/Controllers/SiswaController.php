<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->with('spp')->get();
        $kelas = Kelas::all();
        $spp = Spp::all();
        return view('petugas.siswa.index', compact('siswa', 'kelas', 'spp'));
    }

    public function cari(Request $request)
    {
        $keyword = $request->keyword;
        if (!$keyword) {
            return redirect('/siswa');
        }
        $siswa = Siswa::with('kelas')->with('spp')->where('nama', 'LIKE', '%' . $keyword . '%')->get();
        $kelas = Kelas::all();
        $spp = Spp::all();
        return view('petugas.siswa.index', compact('siswa', 'keyword', 'kelas', 'spp'));
    }

    public function tambah(Request $request)
    {
        $siswa = $request->validate([
            'nisn' => 'required|unique:siswa',
            'nis' => 'required|unique:siswa',
            'nama' => 'required',
            'id_kelas' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required',
            'username' => 'required|unique:siswa',
            'password' => 'required',
        ]);

        $siswa['password'] = Hash::make($request->password);
        Siswa::create($siswa);

        return redirect()->back()->with('success', 'Data berhasil ditambah!');
    }

    public function ubah(Request $request, $id)
    {
        $siswa_request = $request->validate([
            'nisn' => 'required',
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required',
            'username' => 'required',
        ]);

        $siswa = Siswa::find($id);
        $cekNis = Siswa::where('nis', $request->nis)->first();

        if ($cekNis->nisn != $siswa->nisn) {
            return redirect()->back()->with('error', 'NIS tidak boleh sama');
        }

        $siswa->update($siswa_request);

        if ($request->password) {
            $siswa->password = Hash::make($request->password);
            $siswa->save();
        }

        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    public function hapus($id)
    {
        Siswa::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function riwayat()
    {
        $pembayaran = collect(DB::select("SELECT * FROM riwayat_bayar WHERE nisn = '" . Auth::guard('siswa')->user()->nisn . "' ORDER BY tgl_bayar DESC"));

        return view('siswa.pembayaran.riwayat', compact('pembayaran'));
    }

    public function cariRiwayat(Request $request)
    {
        $tanggal = $request->tanggal;
        if (!$tanggal) {
            return redirect('/pembayaran');
        }

        $pembayaran = collect(DB::select("SELECT * FROM riwayat_bayar WHERE tgl_bayar LIKE '%" . $tanggal . "%' ORDER BY tgl_bayar DESC"));
        return view('siswa.pembayaran.riwayat', compact('pembayaran', 'tanggal'));
    }

    public function cetak($tanggal)
    {
        $nisn = Auth::guard('siswa')->user()->nisn;
        $detail_pembayaran = Pembayaran::with('siswa')->where('nisn', $nisn)->where('tgl_bayar', $tanggal)->get();
        $pembayaran = collect(DB::select("SELECT * FROM riwayat_bayar WHERE nisn = '" . $nisn . "' AND tgl_bayar = '" . $tanggal . "'"))->first();

        $pdf = Pdf::loadView('siswa.pembayaran.cetak', compact('pembayaran', 'detail_pembayaran'))->setPaper('a5', 'portrait');
        return $pdf->stream('invoice.pdf');
    }
}
