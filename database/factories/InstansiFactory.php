<?php

namespace Database\Factories;

use App\Enums\TipeInstansi;
use App\Models\Instansi;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstansiFactory extends Factory
{
    protected $model = Instansi::class;

    public function definition(): array
    {
        return [
            'kode_instansi' => fake()->unique()->bothify('??-###'),
            'nama_instansi' => fake()->company(),
            'deskripsi_layanan' => fake()->sentence(),
            'tipe' => fake()->randomElement(TipeInstansi::cases()),
            'aktif' => true,
        ];
    }
}
