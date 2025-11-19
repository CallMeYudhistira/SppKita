<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    protected $fillable = [
        'id_petugas', 'nisn', 'tgl_bayar',
        'bulan_dibayar', 'tahun_dibayar', 'id_spp', 'jumlah_bayar'
    ];

    public function petugas() {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function spp() {
        return $this->belongsTo(Spp::class, 'id_spp', 'id_spp');
    }
}
