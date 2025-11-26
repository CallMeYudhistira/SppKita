<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Pembayaran;
use App\Models\Siswa;
use Carbon\Carbon;
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
        $riwayat = Pembayaran::latest()->take(5)->get();

        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $totalBayarPerBulan = [];
        foreach (range(7, 12) as $i) {
            $totalBayarPerBulan[] = Pembayaran::whereYear('tgl_bayar', now()->year)
                ->whereMonth('tgl_bayar', $i)
                ->sum('jumlah_bayar');
        }
        foreach (range(1, 6) as $i) {
            $totalBayarPerBulan[] = Pembayaran::whereYear('tgl_bayar', now()->year)
                ->whereMonth('tgl_bayar', $i)
                ->sum('jumlah_bayar');
        }

        $bulanIni = Carbon::parse(now())->translatedFormat('F');
        $tahunIni = now()->format('Y');

        $sudahBayar = Pembayaran::where('bulan_dibayar', $bulanIni)
            ->whereYear('tgl_bayar', $tahunIni)
            ->distinct('nisn')
            ->count('nisn');

        $belumBayar = $totalSiswa - $sudahBayar;

        return view('petugas.index', compact(
            'totalSiswa',
            'totalTransaksi',
            'totalPembayaranHariIni',
            'riwayat',
            'logs',
            'bulan',
            'totalBayarPerBulan',
            'sudahBayar',
            'belumBayar'
        ));
    }

    public function siswa(Request $request)
    {
        $nisn = Auth::guard('siswa')->user()->nisn;
        $siswa = Siswa::where('nisn', $nisn)->first();
        $totalSudahBayar = Pembayaran::where('nisn', $nisn)->sum('jumlah_bayar');
        $tagihan = $siswa->spp->nominal;
        $tahun = $siswa->spp->tahun;
        $totalSPP = $siswa->spp->nominal * 12;
        $tunggakan = $totalSPP - $totalSudahBayar;

        $bulan = ['Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $semesterSatu = [];
        $semesterDua = [];
        foreach ($bulan as $i => $b) {
            if ($i < 6) {
                $pembayaran = Pembayaran::whereYear('tgl_bayar', now()->year)
                    ->where('bulan_dibayar', $b)
                    ->where('nisn', $nisn)->first();
                $semesterSatu[] = [
                    'bulan_dibayar' => $b,
                    'jumlah_bayar' => $pembayaran ? $pembayaran->jumlah_bayar : null,
                ];
            } else {
                $pembayaran = Pembayaran::whereYear('tgl_bayar', now()->year)
                    ->where('bulan_dibayar', $b)
                    ->where('nisn', $nisn)->first();
                $semesterDua[] = [
                    'bulan_dibayar' => $b,
                    'jumlah_bayar' => $pembayaran ? $pembayaran->jumlah_bayar : null,
                ];
            }
        }

        return view('siswa.index', compact(
            'siswa',
            'totalSudahBayar',
            'tunggakan',
            'tagihan',
            'tahun',
            'semesterSatu',
            'semesterDua',
        ));
    }
}
