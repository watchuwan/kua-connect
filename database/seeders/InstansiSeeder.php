<?php

namespace Database\Seeders;

use App\Enums\TipeInstansi;
use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            [
                'kode_instansi' => 'DISDUKCAPIL',
                'nama_instansi' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'deskripsi_layanan' => 'Pelayanan administrasi kependudukan',
                'tipe' => TipeInstansi::Dinas,
            ],
            [
                'kode_instansi' => 'DPMPTSP',
                'nama_instansi' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'deskripsi_layanan' => 'Pelayanan perizinan dan non perizinan',
                'tipe' => TipeInstansi::Dinas,
            ],
            [
                'kode_instansi' => 'KEC-BATIPUH',
                'nama_instansi' => 'Kantor Camat Batipuh',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan',
                'tipe' => TipeInstansi::Kecamatan,
            ],
            [
                'kode_instansi' => 'KEC-RAMBATAN',
                'nama_instansi' => 'Kantor Camat Rambatan',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan',
                'tipe' => TipeInstansi::Kecamatan,
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::firstOrCreate(
                ['kode_instansi' => $instansi['kode_instansi']],
                $instansi,
            );
        }
    }
}
