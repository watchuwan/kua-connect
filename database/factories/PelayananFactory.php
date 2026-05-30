<?php

namespace Database\Factories;

use App\Models\Pelayanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelayananFactory extends Factory
{
    protected $model = Pelayanan::class;

    public function definition(): array
    {
        return [
            'nama_pelayanan' => fake()->unique()->randomElement([
                'Pembuatan KTP',
                'Pembuatan KK',
                'Pembuatan Akta Kelahiran',
                'Pembuatan Akta Kematian',
                'Surat Keterangan Pindah',
                'Surat Keterangan Domisili',
                'Surat Keterangan Tidak Mampu',
                'Izin Mendirikan Bangunan',
                'Izin Usaha Mikro Kecil',
                'Pendaftaran Nikah',
            ]),
            'aktif' => true,
        ];
    }
}
