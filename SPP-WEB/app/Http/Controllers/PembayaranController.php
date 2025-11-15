<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->with('spp')->get();
        $pembayaran = Pembayaran::all();
        $bulan = [
            0 => [
                'no' => '07',
                'bulan' => 'Juli',
            ],
            1 => [
                'no' => '08',
                'bulan' => 'Agustus',
            ],
            2 => [
                'no' => '09',
                'bulan' => 'September',
            ],
            3 => [
                'no' => '10',
                'bulan' => 'Oktober',
            ],
            4 => [
                'no' => '11',
                'bulan' => 'November',
            ],
            5 => [
                'no' => '12',
                'bulan' => 'Desember',
            ],
            6 => [
                'no' => '01',
                'bulan' => 'Januari',
            ],
            7 => [
                'no' => '02',
                'bulan' => 'Februari',
            ],
            8 => [
                'no' => '03',
                'bulan' => 'Maret',
            ],
            9 => [
                'no' => '04',
                'bulan' => 'April',
            ],
            10 => [
                'no' => '05',
                'bulan' => 'Mei',
            ],
            11 => [
                'no' => '06',
                'bulan' => 'Juni',
            ],
        ];

        return view('petugas.pembayaran.index', compact('siswa', 'bulan', 'pembayaran'));
    }

    public function bayar(Request $request, $nis)
    {
        $request->validate([
            'bulan_dibayar' => 'required',
            'bulan_dibayar.*' => 'string',
        ]);

        $siswa = Siswa::find($nis);

        $id_spp = $siswa->id_spp;

        $nominal_spp = $siswa->spp->nominal;

        $tahun = now()->format('Y');

        foreach ($request->bulan_dibayar as $bulan) {
            Pembayaran::create([
                'id_petugas' => Auth::guard('petugas')->user()->id_petugas,
                'nisn' => $siswa->nisn,
                'tgl_bayar' => now(),
                'bulan_dibayar' => $bulan,
                'tahun_dibayar' => $tahun,
                'id_spp' => $id_spp,
                'jumlah_bayar' => $nominal_spp,
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function riwayat(){
        $pembayaran = Pembayaran::with('siswa')->with('petugas')->with('spp')->get();
        return view('petugas.pembayaran.riwayat', compact('pembayaran'));
    }
}
