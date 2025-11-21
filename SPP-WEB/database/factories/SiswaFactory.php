<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nisn' => '00' . Random::generate('8', '0-9'),
            'nis' => '10' . Random::generate('6', '0-9'),
            'nama' => $this->faker->name(),
            'id_kelas' => Random::generate('1', '1-9'),
            'alamat' => $this->faker->address(),
            'no_telp' => '08' . Random::generate('10', '0-9'),
            'id_spp' => Random::generate('1', '1-3'),
            'username' => $this->faker->userName(),
            'password' => Hash::make('12345678'),
        ];
    }
}
