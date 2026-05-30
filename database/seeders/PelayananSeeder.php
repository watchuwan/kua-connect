<?php

namespace Database\Seeders;

use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class PelayananSeeder extends Seeder
{
    public function run(): void
    {
        $pelayanans = [
            ['nama_pelayanan' => 'Pembuatan KTP Elektronik'],
            ['nama_pelayanan' => 'Pembuatan Kartu Keluarga'],
            ['nama_pelayanan' => 'Pembuatan Akta Kelahiran'],
            ['nama_pelayanan' => 'Pembuatan Akta Kematian'],
            ['nama_pelayanan' => 'Surat Keterangan Pindah Datang'],
            ['nama_pelayanan' => 'Surat Keterangan Domisili'],
            ['nama_pelayanan' => 'Surat Keterangan Tidak Mampu'],
            ['nama_pelayanan' => 'Izin Mendirikan Bangunan (IMB)'],
            ['nama_pelayanan' => 'Izin Usaha Mikro Kecil (IUMK)'],
            ['nama_pelayanan' => 'Pendaftaran Nikah'],
        ];

        foreach ($pelayanans as $pelayanan) {
            Pelayanan::firstOrCreate(
                ['nama_pelayanan' => $pelayanan['nama_pelayanan']],
                $pelayanan,
            );
        }
    }
}
