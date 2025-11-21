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
        // Kelas 12
        Kelas::create([
            'nama_kelas' => '12',
            'kompetensi_keahlian' => 'Rekayasa Perangkat Lunak',
        ]);
        
        Kelas::create([
            'nama_kelas' => '12',
            'kompetensi_keahlian' => 'Teknik Komputer dan Jaringan',
        ]);

        // Kelas 11
        Kelas::create([
            'nama_kelas' => '11',
            'kompetensi_keahlian' => 'Rekayasa Perangkat Lunak',
        ]);
        
        Kelas::create([
            'nama_kelas' => '11',
            'kompetensi_keahlian' => 'Teknik Komputer dan Jaringan',
        ]);
        
        Kelas::create([
            'nama_kelas' => '11',
            'kompetensi_keahlian' => 'Teknik Elektronika Industri',
        ]);

        // Kelas 10
        Kelas::create([
            'nama_kelas' => '10',
            'kompetensi_keahlian' => 'Rekayasa Perangkat Lunak',
        ]);
        
        Kelas::create([
            'nama_kelas' => '10',
            'kompetensi_keahlian' => 'Teknik Komputer dan Jaringan',
        ]);
        
        Kelas::create([
            'nama_kelas' => '10',
            'kompetensi_keahlian' => 'Teknik Elektronika Industri',
        ]);
        
        Kelas::create([
            'nama_kelas' => '10',
            'kompetensi_keahlian' => 'Teknik Pendingin dan Tata Udara',
        ]);

        Spp::create([
            'tahun' => '2023',
            'nominal' => '200000'
        ]);

        Spp::create([
            'tahun' => '2024',
            'nominal' => '220000'
        ]);

        Spp::create([
            'tahun' => '2025',
            'nominal' => '250000'
        ]);

        Siswa::factory(50)->create();
        Petugas::factory(20)->create();
    }
}
