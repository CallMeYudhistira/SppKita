<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
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
        if ($id_kelas != "semua") {
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

        return redirect('/pembayaran/detail/' . $nisn)->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function riwayat()
    {
        $pembayaran = collect(DB::select('SELECT * FROM riwayat_pembayaran'));
        $kelas = Kelas::all();

        return view('petugas.pembayaran.riwayat', compact('pembayaran', 'kelas'));
    }

    public function cariRiwayat(Request $request)
    {
        $keyword = $request->keyword;
        $id_kelas = $request->id_kelas;
        $kelas = Kelas::all();
        $pembayaran = collect(DB::select("SELECT * FROM riwayat_pembayaran WHERE nama LIKE '%" . $keyword . "%'"));
        if ($id_kelas != "semua") {
            $pembayaran = collect(DB::select("SELECT * FROM riwayat_pembayaran WHERE nama LIKE '%" . $keyword . "%' AND id_kelas = '" . $id_kelas . "'"));
        }

        return view('petugas.pembayaran.riwayat', compact('pembayaran', 'keyword', 'id_kelas', 'kelas'));
    }

    public function detail($nisn)
    {
        $s = Siswa::with('kelas')->with('spp')->where('nisn', $nisn)->first();
        $pembayaran = Pembayaran::with('petugas')->where('nisn', $nisn)->get();
        $siswa = collect(DB::select("SELECT * FROM riwayat_pembayaran WHERE nisn = '" . $nisn . "'"))->first();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.pembayaran.detail', compact('pembayaran', 'siswa', 'bulan', 's'));
    }

    public function cetak($id)
    {
        $pembayaran = Pembayaran::with('siswa')->with('spp')->with('petugas')->find($id);

        $pdf = Pdf::loadView('petugas.pembayaran.cetak', compact('pembayaran'))->setPaper('a5', 'portrait');
        return $pdf->stream('invoice.pdf');
    }

    public function cetakKartu($nisn)
    {
        $pembayaran = Pembayaran::with('petugas')->where('nisn', $nisn)->get();
        $siswa = collect(DB::select("SELECT * FROM riwayat_pembayaran WHERE nisn = '" . $nisn . "'"))->first();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.pembayaran.kartu', compact('pembayaran', 'siswa', 'bulan'));
    }

    public function hapus($id)
    {
        Pembayaran::find($id)->delete();
        return redirect()->back()->with('success', 'Hapus Transaksi Berhasil!');
    }

    public function gagal()
    {
        $kelas = Kelas::all();
        $pembayaran = Pembayaran::onlyTrashed()->with('siswa')->get();

        return view('petugas.pembayaran.dihapus', compact('kelas', 'pembayaran'));
    }

    public function filterGagal(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;

        if (!$dari && !$sampai) {
            return redirect('/pembayaran/riwayat/gagal');
        }

        $pembayaran = Pembayaran::onlyTrashed()
            ->with('siswa')
            ->whereBetween('tgl_bayar', [$dari, $sampai])
            ->get();

        return view('petugas.pembayaran.dihapus', compact('pembayaran', 'dari', 'sampai'));
    }

}
