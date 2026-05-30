<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            ['nama_kecamatan' => 'Kecamatan Batipuh'],
            ['nama_kecamatan' => 'Kecamatan Batipuh Selatan'],
            ['nama_kecamatan' => 'Kecamatan Lima Kaum'],
            ['nama_kecamatan' => 'Kecamatan Lintau Buo'],
            ['nama_kecamatan' => 'Kecamatan Lintau Buo Utara'],
            ['nama_kecamatan' => 'Kecamatan Padang Ganting'],
            ['nama_kecamatan' => 'Kecamatan Pariangan'],
            ['nama_kecamatan' => 'Kecamatan Rambatan'],
            ['nama_kecamatan' => 'Kecamatan Salimpaung'],
            ['nama_kecamatan' => 'Kecamatan Sepuluh Koto'],
            ['nama_kecamatan' => 'Kecamatan Sungai Tarab'],
            ['nama_kecamatan' => 'Kecamatan Sungayang'],
            ['nama_kecamatan' => 'Kecamatan Tanjuang Barang'],
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::firstOrCreate($kecamatan);
        }
    }
}
