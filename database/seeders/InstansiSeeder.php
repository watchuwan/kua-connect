<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $instansis = [
            // === Dinas ===
            [
                'kode_instansi' => 'DISDUKCAPIL',
                'nama_instansi' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'deskripsi_layanan' => 'Pelayanan administrasi kependudukan dan pencatatan sipil',
                'tipe' => 'dinas',
                'kecamatan' => 'Tigaraksa',
            ],
            [
                'kode_instansi' => 'DPMPTSP',
                'nama_instansi' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                'deskripsi_layanan' => 'Pelayanan perizinan dan non perizinan terpadu',
                'tipe' => 'dinas',
                'kecamatan' => 'Tigaraksa',
            ],
            [
                'kode_instansi' => 'DINKES',
                'nama_instansi' => 'Dinas Kesehatan',
                'deskripsi_layanan' => 'Pelayanan kesehatan masyarakat',
                'tipe' => 'dinas',
                'kecamatan' => 'Tigaraksa',
            ],
            [
                'kode_instansi' => 'DINSOS',
                'nama_instansi' => 'Dinas Sosial',
                'deskripsi_layanan' => 'Pelayanan bantuan sosial dan kesejahteraan masyarakat',
                'tipe' => 'dinas',
                'kecamatan' => 'Tigaraksa',
            ],
            [
                'kode_instansi' => 'DISDIK',
                'nama_instansi' => 'Dinas Pendidikan',
                'deskripsi_layanan' => 'Pelayanan administrasi pendidikan',
                'tipe' => 'dinas',
                'kecamatan' => 'Tigaraksa',
            ],
            // === Kecamatan ===
            [
                'kode_instansi' => 'KEC-TIGARAKSA',
                'nama_instansi' => 'Kecamatan Tigaraksa',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Tigaraksa',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Tigaraksa',
            ],
            [
                'kode_instansi' => 'KEC-BALARAJA',
                'nama_instansi' => 'Kecamatan Balaraja',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Balaraja',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Balaraja',
            ],
            [
                'kode_instansi' => 'KEC-CIKUPA',
                'nama_instansi' => 'Kecamatan Cikupa',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Cikupa',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Cikupa',
            ],
            [
                'kode_instansi' => 'KEC-CURUG',
                'nama_instansi' => 'Kecamatan Curug',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Curug',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Curug',
            ],
            [
                'kode_instansi' => 'KEC-PASAR-KEMIS',
                'nama_instansi' => 'Kecamatan Pasar Kemis',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Pasar Kemis',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Pasar Kemis',
            ],
            [
                'kode_instansi' => 'KEC-LEGOK',
                'nama_instansi' => 'Kecamatan Legok',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Legok',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Legok',
            ],
            [
                'kode_instansi' => 'KEC-PAGEDANGAN',
                'nama_instansi' => 'Kecamatan Pagedangan',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Pagedangan',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Pagedangan',
            ],
            [
                'kode_instansi' => 'KEC-SEPATAN',
                'nama_instansi' => 'Kecamatan Sepatan',
                'deskripsi_layanan' => 'Pelayanan administrasi kecamatan Sepatan',
                'tipe' => 'kecamatan',
                'kecamatan' => 'Sepatan',
            ],
        ];

        foreach ($instansis as $instansi) {
            Instansi::firstOrCreate(
                ['kode_instansi' => $instansi['kode_instansi']],
                array_merge($instansi, ['aktif' => true]),
            );
        }
    }
}
