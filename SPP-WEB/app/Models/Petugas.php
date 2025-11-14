<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Petugas extends Authenticatable
{
    use HasFactory;
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    protected $fillable = ['username', 'password', 'nama_petugas', 'level'];

    public function pembayaran() {
        return $this->hasMany(Pembayaran::class, 'id_petugas', 'id_petugas');
    }
}
