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
        $layananKtp = Pelayanan::where('nama_pelayanan', 'Pembuatan KTP Elektronik')->first();
        $layananKk = Pelayanan::where('nama_pelayanan', 'Pembuatan Kartu Keluarga')->first();
        $layananAkta = Pelayanan::where('nama_pelayanan', 'Pembuatan Akta Kelahiran')->first();

        if (!$layananKtp) {
            return;
        }

        $yesterday = now()->subDay()->format('Ymd');
        $today = now()->format('Ymd');

        $pendaftarans = [
            // === HARI INI ===
            [
                'nomor_antrean' => "A-{$today}0001",
                'pelayanan_id' => $layananKtp->id,
                'data' => [
                    'nik' => '3603010101900001',
                    'nama_lengkap' => 'Andi Pratama',
                    'tempat_lahir' => 'Tangerang',
                    'tanggal_lahir' => '1990-01-01',
                    'jenis_kelamin' => 'Laki-laki',
                    'alamat' => 'Kp. Cijeruk, Desa Cijeruk, Kec. Tigaraksa',
                    'no_hp' => '081234567890',
                    'email' => 'andi@example.com',
                    'keterangan' => '',
                ],
                'status' => StatusPendaftaran::Waiting,
                'created_at' => now()->subHours(1),
            ],
            [
                'nomor_antrean' => "A-{$today}0002",
                'pelayanan_id' => $layananKtp->id,
                'data' => [
                    'nik' => '3603020101900002',
                    'nama_lengkap' => 'Siti Rahmawati',
                    'tempat_lahir' => 'Tangerang',
                    'tanggal_lahir' => '1992-05-15',
                    'jenis_kelamin' => 'Perempuan',
                    'alamat' => 'Perumahan Graha Raya, Blok A5 No. 10, Kec. Balaraja',
                    'no_hp' => '081234567891',
                    'email' => 'siti@example.com',
                    'keterangan' => '',
                ],
                'status' => StatusPendaftaran::Serving,
                'waktu_dilayani' => now()->subMinutes(15),
                'created_at' => now()->subHours(2),
            ],
            [
                'nomor_antrean' => "A-{$today}0003",
                'pelayanan_id' => $layananKk->id,
                'data' => [
                    'nik' => '3603030101900003',
                    'nama_lengkap' => 'Budi Santoso',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '1988-08-20',
                    'jenis_kelamin' => 'Laki-laki',
                    'alamat' => 'Jl. Raya Serang Km. 15, Kec. Cikupa',
                    'no_hp' => '081234567892',
                    'email' => 'budi@example.com',
                    'keterangan' => 'KK hilang, mohon diterbitkan baru',
                ],
                'status' => StatusPendaftaran::Done,
                'waktu_dilayani' => now()->subHours(3),
                'waktu_selesai' => now()->subHours(2),
                'created_at' => now()->subHours(4),
            ],
            // === KEMARIN ===
            [
                'nomor_antrean' => "A-{$yesterday}0001",
                'pelayanan_id' => $layananKtp->id,
                'data' => [
                    'nik' => '3603040101900004',
                    'nama_lengkap' => 'Dewi Anggraini',
                    'tempat_lahir' => 'Tangerang',
                    'tanggal_lahir' => '1995-12-10',
                    'jenis_kelamin' => 'Perempuan',
                    'alamat' => 'Kp. Sindang, Desa Sindang Jaya, Kec. Sindang Jaya',
                    'no_hp' => '081234567893',
                    'email' => 'dewi@example.com',
                    'keterangan' => '',
                ],
                'status' => StatusPendaftaran::Done,
                'waktu_dilayani' => now()->subDay()->addHours(9),
                'waktu_selesai' => now()->subDay()->addHours(10),
                'created_at' => now()->subDay()->addHours(8),
            ],
            [
                'nomor_antrean' => "A-{$yesterday}0002",
                'pelayanan_id' => $layananAkta->id,
                'data' => [
                    'nik' => '3603050101900005',
                    'nama_lengkap' => 'Ahmad Fauzi',
                    'tempat_lahir' => 'Tangerang',
                    'tanggal_lahir' => '1985-03-25',
                    'jenis_kelamin' => 'Laki-laki',
                    'alamat' => 'Perum Taman Balaraja, Blok C2 No. 5, Kec. Balaraja',
                    'no_hp' => '081234567894',
                    'email' => 'ahmad@example.com',
                    'keterangan' => 'Mengurus akta kelahiran anak',
                ],
                'status' => StatusPendaftaran::Skipped,
                'created_at' => now()->subDay()->addHours(7),
            ],
            [
                'nomor_antrean' => "A-{$yesterday}0003",
                'pelayanan_id' => $layananAkta->id,
                'data' => [
                    'nik' => '3603060101900006',
                    'nama_lengkap' => 'Rina Kusuma',
                    'tempat_lahir' => 'Tangerang',
                    'tanggal_lahir' => '1998-07-08',
                    'jenis_kelamin' => 'Perempuan',
                    'alamat' => 'Jl. Merdeka No. 45, Kec. Curug',
                    'no_hp' => '081234567895',
                    'email' => 'rina@example.com',
                    'keterangan' => '',
                ],
                'status' => StatusPendaftaran::Done,
                'waktu_dilayani' => now()->subDay()->addHours(10),
                'waktu_selesai' => now()->subDay()->addHours(11),
                'created_at' => now()->subDay()->addHours(9),
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
