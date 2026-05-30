<?php

namespace Database\Seeders;

use App\Enums\StatusPendaftaran;
use App\Models\Pendaftaran;
use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    public function run(): void
    {
        $pelayanan = Pelayanan::query()->first();

        if (!$pelayanan) {
            return;
        }

        $pendaftarans = [
            [
                'nomor_antrean' => 'A-202605290001',
                'pelayanan_id' => $pelayanan->id,
                'data' => [
                    'nik' => '3273010101900001',
                    'nama' => 'Andi Pratama',
                    'alamat' => 'Jl. Merdeka No. 123, Batipuh',
                    'no_hp' => '081234567890',
                ],
                'status' => StatusPendaftaran::Waiting,
            ],
            [
                'nomor_antrean' => 'A-202605290002',
                'pelayanan_id' => $pelayanan->id,
                'data' => [
                    'nik' => '3273010101900002',
                    'nama' => 'Siti Rahmawati',
                    'alamat' => 'Jl. Sudirman No. 45, Rambatan',
                    'no_hp' => '081234567891',
                ],
                'status' => StatusPendaftaran::Serving,
                'waktu_dilayani' => now()->subMinutes(15),
            ],
            [
                'nomor_antrean' => 'A-202605290003',
                'pelayanan_id' => $pelayanan->id,
                'data' => [
                    'nik' => '3273010101900003',
                    'nama' => 'Budi Santoso',
                    'alamat' => 'Jl. Diponegoro No. 78, Lima Kaum',
                    'no_hp' => '081234567892',
                ],
                'status' => StatusPendaftaran::Done,
                'waktu_dilayani' => now()->subHours(2),
                'waktu_selesai' => now()->subHours(1),
            ],
            [
                'nomor_antrean' => 'A-202605290004',
                'pelayanan_id' => $pelayanan->id,
                'data' => [
                    'nik' => '3273010101900004',
                    'nama' => 'Dewi Anggraini',
                    'alamat' => 'Jl. Ahmad Yani No. 12, Sungayang',
                    'no_hp' => '081234567893',
                ],
                'status' => StatusPendaftaran::Skipped,
            ],
        ];

        foreach ($pendaftarans as $pendaftaran) {
            Pendaftaran::firstOrCreate(
                ['nomor_antrean' => $pendaftaran['nomor_antrean']],
                $pendaftaran,
            );
        }
    }
}
