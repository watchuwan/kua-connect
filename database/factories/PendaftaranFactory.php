<?php

namespace Database\Factories;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendaftaranFactory extends Factory
{
    protected $model = Pendaftaran::class;

    public function definition(): array
    {
        return [
            'nomor_antrean' => fake()->unique()->regexify('A-\d{12}'),
            'data' => [
                'nik' => fake()->nik(),
                'nama' => fake()->name(),
                'alamat' => fake()->address(),
                'no_hp' => fake()->phoneNumber(),
            ],
            'status' => fake()->randomElement(StatusPendaftaran::cases()),
            'waktu_dilayani' => null,
            'waktu_selesai' => null,
        ];
    }

    public function waiting(): static
    {
        return $this->state(fn () => [
            'status' => StatusPendaftaran::Waiting,
            'waktu_dilayani' => null,
            'waktu_selesai' => null,
        ]);
    }

    public function serving(): static
    {
        return $this->state(fn () => [
            'status' => StatusPendaftaran::Serving,
            'waktu_dilayani' => now(),
            'waktu_selesai' => null,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status' => StatusPendaftaran::Done,
            'waktu_dilayani' => now()->subHour(),
            'waktu_selesai' => now(),
        ]);
    }
}
