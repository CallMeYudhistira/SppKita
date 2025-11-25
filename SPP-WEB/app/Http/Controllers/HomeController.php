<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function petugas()
    {
        $totalSiswa = Siswa::count();
        $totalTransaksi = Pembayaran::count();
        $totalPembayaranHariIni = Pembayaran::whereDate('tgl_bayar', now()->toDateString())->sum('jumlah_bayar');
        $logs = Log::with('petugas')->with('siswa')->latest()->take(10)->get();
        $pembayaran = Pembayaran::whereYear('tgl_bayar', now()->format('Y'))->get();

        $riwayat = Pembayaran::latest()->take(5)->get();

        $color = ['#acf', '#aed', '#eba', '#fea', '#f49', '#abc', '#cba', '#bac', '#cab', '#edc', '#efa', '#fce'];
        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $bulanNomer = ['07', '08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06'];

        return view('petugas.index', compact(
            'totalSiswa',
            'totalTransaksi',
            'totalPembayaranHariIni',
            'riwayat',
            'logs',
            'bulan',
            'color',
            'pembayaran',
            'bulanNomer'
        ));
    }

    public function siswa(Request $request)
    {
        $nisn = Auth::guard('siswa')->user()->nisn;

        $siswa = Siswa::where('nisn', $nisn)->first();

        $totalSudahBayar = Pembayaran::where('nisn', $nisn)->sum('jumlah_bayar');

        $riwayatTerakhir = Pembayaran::where('nisn', $nisn)
            ->latest()
            ->first();

        $tagihan = $siswa->spp->nominal;
        $totalSPP = $siswa->spp->nominal * 12;

        $tunggakan = $totalSPP - $totalSudahBayar;

        return view('siswa.index', compact(
            'siswa',
            'totalSudahBayar',
            'riwayatTerakhir',
            'tunggakan',
            'tagihan'
        ));
    }
}
