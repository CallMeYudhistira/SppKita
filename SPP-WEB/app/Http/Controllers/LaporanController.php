<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->with('spp')->get();
        $pembayaran = Pembayaran::all();
        $kelas = Kelas::all();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.laporan.index', compact('siswa', 'bulan', 'pembayaran', 'kelas'));
    }

    public function cari(Request $request)
    {
        $id_kelas = $request->id_kelas;
        $siswa = Siswa::with('kelas')->with('spp')->get();
        if($id_kelas != "semua"){
            $siswa = $siswa->where('id_kelas', $id_kelas);
        }
        $pembayaran = Pembayaran::all();
        $kelas = Kelas::all();
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];

        return view('petugas.laporan.index', compact('siswa', 'bulan', 'pembayaran', 'kelas', 'id_kelas'));
    }

    public function cetak(Request $request) {
        $id_kelas = $request->id_kelas;

        if($id_kelas == ""){
            return redirect()->back()->with('error', 'Pilih Kelas Terlebih Dahulu!');
        }

        $siswa = Siswa::with('kelas')->with('spp')->where('id_kelas', $id_kelas)->get();
        $pembayaran = Pembayaran::all();
        $kelas = Kelas::find($id_kelas);
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $totalKelas = 0;

        $pdf = Pdf::loadView('petugas.laporan.cetak', compact('siswa', 'bulan', 'pembayaran', 'kelas', 'totalKelas'))->setPaper('a4', 'landscape');
        return $pdf->stream('invoice.pdf');
    }
}
