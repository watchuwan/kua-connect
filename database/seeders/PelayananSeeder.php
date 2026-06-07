<?php

namespace Database\Seeders;

use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class PelayananSeeder extends Seeder
{
    public function run(): void
    {
        $pelayanans = [
            ['nama_pelayanan' => 'Pendaftaran Nikah', 'slug' => 'pendaftaran-nikah', 'aktif' => true],
            ['nama_pelayanan' => 'Sertifikasi Wakaf', 'slug' => 'sertifikasi-wakaf', 'aktif' => true],
            ['nama_pelayanan' => 'Rekomendasi Nikah Keluar', 'slug' => 'rekomendasi-nikah-keluar', 'aktif' => true],
            ['nama_pelayanan' => 'Surat Keterangan Mualaf', 'slug' => 'surat-keterangan-mualaf', 'aktif' => true],
            ['nama_pelayanan' => 'Kalibrasi Arah Kiblat', 'slug' => 'kalibrasi-arah-kiblat', 'aktif' => true],
        ];

        foreach ($pelayanans as $pelayanan) {
            Pelayanan::firstOrCreate(
                ['nama_pelayanan' => $pelayanan['nama_pelayanan']],
                $pelayanan,
            );
        }
    }
}
