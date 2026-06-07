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
        $nikah = Pelayanan::where('slug', 'pendaftaran-nikah')->first();
        if (!$nikah) {
            return;
        }

        $today = now()->format('Ymd');
        $yesterday = now()->subDay()->format('Ymd');

        $pendaftarans = [
            [
                'nomor_antrean' => "A-{$today}0001",
                'pelayanan_id' => $nikah->id,
                'data' => [
                    'kategori_pemohon' => 'Warga Lokal',
                    'tanggal_akad' => '2026-06-20',
                    'lokasi_akad' => 'KUA',
                    'mas_kawin' => 'Emas 10 Gram',
                    'pria_nama' => 'Ahmad Fauzi',
                    'pria_nik' => '3603010101900001',
                    'wanita_nama' => 'Siti Aisyah',
                    'wanita_nik' => '3603020101900002',
                    'wali_nama' => 'H. Abdullah',
                    'wali_hub' => 'Ayah Kandung',
                ],
                'status' => StatusPendaftaran::Pending,
                'created_at' => now()->subHours(2),
            ],
            [
                'nomor_antrean' => "A-{$today}0002",
                'pelayanan_id' => $nikah->id,
                'data' => [
                    'kategori_pemohon' => 'Warga Lokal',
                    'tanggal_akad' => '2026-06-22',
                    'lokasi_akad' => 'Luar KUA',
                    'mas_kawin' => 'Emas 20 Gram + Alat Shalat',
                    'pria_nama' => 'Budi Santoso',
                    'pria_nik' => '3603030101900003',
                    'wanita_nama' => 'Dewi Anggraini',
                    'wanita_nik' => '3603040101900004',
                    'wali_nama' => 'Slamet Riyadi',
                    'wali_hub' => 'Paman',
                ],
                'status' => StatusPendaftaran::VerifikasiFisik,
                'catatan' => 'Berkas lengkap, silakan datang ke KUA untuk verifikasi fisik',
                'waktu_dilayani' => now()->subHours(1),
                'created_at' => now()->subHours(4),
            ],
            [
                'nomor_antrean' => "A-{$today}0003",
                'pelayanan_id' => $nikah->id,
                'data' => [
                    'kategori_pemohon' => 'Warga Lokal',
                    'tanggal_akad' => '2026-06-15',
                    'lokasi_akad' => 'KUA',
                    'mas_kawin' => 'Emas 15 Gram',
                    'pria_nama' => 'Abdul Hakim',
                    'pria_nik' => '3603050101900005',
                    'wanita_nama' => 'Fatimah Zahra',
                    'wanita_nik' => '3603060101900006',
                    'wali_nama' => 'K.H. Abdullah',
                    'wali_hub' => 'Ayah Kandung',
                ],
                'status' => StatusPendaftaran::Selesai,
                'no_surat' => 'B.393/KUA.01/06/2026',
                'waktu_dilayani' => now()->subDays(3)->addHours(8),
                'waktu_selesai' => now()->subDays(3)->addHours(11),
                'created_at' => now()->subDays(5),
            ],
            [
                'nomor_antrean' => "A-{$yesterday}0001",
                'pelayanan_id' => $nikah->id,
                'data' => [
                    'kategori_pemohon' => 'Warga Luar Daerah (Tumpangan)',
                    'tanggal_akad' => '2026-06-28',
                    'lokasi_akad' => 'KUA',
                    'mas_kawin' => 'Emas 25 Gram',
                    'pria_nama' => 'Reza Pratama',
                    'pria_nik' => '3603070101900007',
                    'wanita_nama' => 'Rina Kusuma',
                    'wanita_nik' => '3603080101900008',
                    'kua_asal_rekomendasi' => 'KUA Kecamatan Serang',
                    'no_surat_rekomendasi' => 'R.202/KUA.02/05/2026',
                    'wali_nama' => 'Drs. H. Hasan Basri',
                    'wali_hub' => 'Wali Hakim',
                ],
                'status' => StatusPendaftaran::PerluRevisi,
                'catatan' => 'Dokumen surat rekomendasi dari KUA asal belum lengkap, harap upload ulang',
                'created_at' => now()->subDay()->addHours(3),
            ],
            [
                'nomor_antrean' => "A-{$yesterday}0002",
                'pelayanan_id' => $nikah->id,
                'data' => [
                    'kategori_pemohon' => 'Warga Lokal',
                    'tanggal_akad' => '2026-06-25',
                    'lokasi_akad' => 'Luar KUA',
                    'mas_kawin' => 'Emas 10 Gram + Alat Shalat',
                    'pria_nama' => 'Hendra Gunawan',
                    'pria_nik' => '3603090101900009',
                    'pria_status' => 'Duda',
                    'wanita_nama' => 'Nurul Hidayah',
                    'wanita_nik' => '3603100101900010',
                    'wanita_status' => 'Janda',
                    'wali_nama' => 'M. Syafii',
                    'wali_hub' => 'Saudara Laki',
                ],
                'status' => StatusPendaftaran::MenungguPembayaran,
                'catatan' => 'Harap upload bukti bayar PNBP Rp 600.000',
                'created_at' => now()->subDay()->addHours(6),
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
