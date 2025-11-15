<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(){
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
}
