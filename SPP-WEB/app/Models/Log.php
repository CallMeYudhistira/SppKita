<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['aktifitas', 'id_petugas', 'nisn'];

    public function petugas() {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
