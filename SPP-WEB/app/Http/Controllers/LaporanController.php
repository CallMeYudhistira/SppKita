<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $pembayaran = collect(DB::select('SELECT * FROM riwayat_bayar'));

        return view('petugas.laporan.index', compact('pembayaran'));
    }

    public function filter(Request $request)
    {
        $filter = $request->filter ?? 'all';

        $query = DB::table('riwayat_bayar');

        if ($filter == 'today') {
            $query->whereDate('tgl_bayar', Carbon::today());
        }

        if ($filter == 'month') {
            $query->whereMonth('tgl_bayar', Carbon::now()->month)
                ->whereYear('tgl_bayar', Carbon::now()->year);
        }

        if ($filter == 'year') {
            $query->whereYear('tgl_bayar', Carbon::now()->year);
        }

        if ($filter == 'manual') {
            $first = $request->first;
            $second = $request->second;

            if ($first && $second) {
                $query->whereBetween('tgl_bayar', [$first, $second]);
            } else {
                return redirect('/laporan');
            }
        }

        $pembayaran = $query->get();

        return view('petugas.laporan.index', [
            'pembayaran' => $pembayaran,
            'first' => $request->first,
            'second' => $request->second,
            'filter' => $filter,
        ]);
    }

    public function cetakExcel(Request $request)
    {
        $fileName = 'rekap_pembayaran_' . date('Y-m-d_H-i-s') . '.xls';
        $filter = $request->filter ?? 'all';

        $query = DB::table('laporan');

        if ($filter == 'today') {
            $query->whereDate('tgl_bayar', Carbon::today());
        }

        if ($filter == 'month') {
            $query->whereMonth('tgl_bayar', Carbon::now()->month)
                ->whereYear('tgl_bayar', Carbon::now()->year);
        }

        if ($filter == 'year') {
            $query->whereYear('tgl_bayar', Carbon::now()->year);
        }

        if ($filter == 'manual') {
            $first = $request->first;
            $second = $request->second;

            if ($first && $second) {
                $query->whereBetween('tgl_bayar', [$first, $second]);
            }
        }

        $pembayaran = $query->orderBy('tgl_bayar', 'DESC')->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function () use ($pembayaran) {
            echo "<table border='1'>";
            echo "<tr>
                <th>Tanggal Bayar</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Tahun Dibayar</th>
                <th>Bulan Dibayar</th>
                <th>Total Bayar</th>
                <th>Nama Petugas</th>
            </tr>";
            foreach ($pembayaran as $p) {
                echo "<tr>
                    <td>" . Carbon::parse($p->tgl_bayar)->isoFormat('DD/MMM/Y') . "</td>
                    <td>{$p->nis}</td>
                    <td>{$p->nama}</td>
                    <td>{$p->nama_kelas} {$p->kompetensi_keahlian}</td>
                    <td>{$p->tahun_dibayar}</td>
                    <td>{$p->bulan_dibayar}</td>
                    <td>{$p->total_bayar}</td>
                    <td>{$p->nama_petugas}</td>
                </tr>";
            }
            echo "</table>";
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetakPDF(Request $request)
    {
        $filter = $request->filter ?? 'all';

        $query = DB::table('laporan');

        if ($filter == 'today') {
            $filter = "Hari ini";
            $query->whereDate('tgl_bayar', Carbon::today());
        }

        if ($filter == 'month') {
            $filter = "Bulan ini";
            $query->whereMonth('tgl_bayar', Carbon::now()->month)
                ->whereYear('tgl_bayar', Carbon::now()->year);
        }

        if ($filter == 'year') {
            $filter = "Tahun ini";
            $query->whereYear('tgl_bayar', Carbon::now()->year);
        }

        $first = '-';
        $second = '-';
        if ($filter == 'manual') {
            $first = $request->first;
            $second = $request->second;

            if ($first != '-' && $second != '-') {
                $query->whereBetween('tgl_bayar', [$first, $second]);
            }
        }

        if($filter == 'all'){
            $filter = "Semua";
        }

        $pembayaran = $query->orderBy('tgl_bayar', 'DESC')->get();

        $pdf = Pdf::loadView('petugas.laporan.cetak', compact('pembayaran', 'filter', 'first', 'second'))->setPaper('a4', 'landscape');
        return $pdf->stream('rekap_pembayaran_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
