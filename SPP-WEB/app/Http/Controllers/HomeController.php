<?php

namespace App\Http\Controllers;

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
        $totalTunggakan = DB::table('spp')
            ->leftJoin('siswa', 'spp.id_spp', '=', 'siswa.id_spp')
            ->leftJoin('pembayaran', function ($join) {
                $join->on('pembayaran.nisn', '=', 'siswa.nisn');
            })
            ->select('spp.tahun', 'spp.nominal')
            ->get()
            ->sum('nominal') - Pembayaran::sum('jumlah_bayar');

        $riwayat = Pembayaran::latest()->take(5)->get();

        return view('petugas.index', compact(
            'totalSiswa',
            'totalTransaksi',
            'totalPembayaranHariIni',
            'totalTunggakan',
            'riwayat'
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
