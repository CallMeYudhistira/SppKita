<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Kelas;
use App\Models\Petugas;
use App\Models\Siswa;
use App\Models\Spp;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Kelas::create([
            'nama_kelas' => '12 A',
            'kompetensi_keahlian' => 'Rekayasa Perangkat Lunak',
        ]);

        Spp::create([
            'tahun' => '2025',
            'nominal' => '200000'
        ]);

        Siswa::create([
            'nisn' => '0012345678',
            'nis' => '10123456',
            'nama' => 'siswa',
            'id_kelas' => '1',
            'alamat' => '-',
            'no_telp' => '08123456789',
            'id_spp' => '1',
            'username' => 'siswa',
            'password' => Hash::make('siswa'),
        ]);

        Petugas::create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'nama_petugas' => 'admin',
            'level' => 'admin',
        ]);

        Petugas::create([
            'username' => 'petugas',
            'password' => Hash::make('petugas'),
            'nama_petugas' => 'petugas',
            'level' => 'petugas',
        ]);
    }
}
