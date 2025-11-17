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

class PembayaranController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->with('spp')->get();
        $pembayaran = Pembayaran::all();
        $kelas = Kelas::all();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.pembayaran.index', compact('siswa', 'bulan', 'pembayaran', 'kelas'));
    }

    public function cari(Request $request)
    {
        $keyword = $request->keyword;
        $id_kelas = $request->id_kelas;
        $siswa = Siswa::with('kelas')->with('spp')->where('nama', 'LIKE', '%' . $keyword . '%')->get();
        if($id_kelas != "semua"){
            $siswa = $siswa->where('id_kelas', $id_kelas);
        }
        $pembayaran = Pembayaran::all();
        $kelas = Kelas::all();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.pembayaran.index', compact('siswa', 'keyword', 'bulan', 'pembayaran', 'kelas', 'id_kelas'));
    }

    public function bayar(Request $request, $nisn)
    {
        $request->validate([
            'bulan_dibayar' => 'required|array',
            'bulan_dibayar.*' => 'string'
        ]);

        $siswa = Siswa::find($nisn);
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $id_spp = $siswa->id_spp;
        $nominal_spp = $siswa->spp->nominal;

        foreach ($request->bulan_dibayar as $bulan) {
            $cek = Pembayaran::where('nisn', $siswa->nisn)
                ->where('bulan_dibayar', $bulan)
                ->where('tahun_dibayar', $siswa->spp->tahun)
                ->exists();

            if ($cek) {
                continue;
            }

            Pembayaran::create([
                'id_petugas' => Auth::guard('petugas')->user()->id_petugas,
                'nisn' => $siswa->nisn,
                'tgl_bayar' => now(),
                'bulan_dibayar' => $bulan,
                'tahun_dibayar' => $siswa->spp->tahun,
                'id_spp' => $id_spp,
                'jumlah_bayar' => $nominal_spp,
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function riwayat()
    {
        $pembayaran = collect(DB::select('SELECT * FROM riwayat_bayar ORDER BY tgl_bayar DESC'));

        return view('petugas.pembayaran.riwayat', compact('pembayaran'));
    }

    public function cariRiwayat(Request $request)
    {
        $keyword = $request->keyword;
        if (!$keyword) {
            return redirect('/pembayaran/riwayat');
        }

        $pembayaran = collect(DB::select("SELECT * FROM riwayat_bayar WHERE nama LIKE '%" . $keyword . "%' ORDER BY tgl_bayar DESC"));
        return view('petugas.pembayaran.riwayat', compact('pembayaran', 'keyword'));
    }

    public function cetak($nisn, $tanggal, $tahun)
    {
        $detail_pembayaran = Pembayaran::with('siswa')->where('nisn', $nisn)->where('tgl_bayar', $tanggal)->where('tahun_dibayar', $tahun)->get();
        $pembayaran = collect(DB::select("SELECT * FROM riwayat_bayar WHERE nisn = '" . $nisn . "' AND tgl_bayar = '" . $tanggal . "'"))->first();

        $pdf = Pdf::loadView('petugas.pembayaran.cetak', compact('pembayaran', 'detail_pembayaran'))->setPaper('a5', 'portrait');
        return $pdf->stream('invoice.pdf');
    }
}
