<?php

namespace Database\Seeders;

use App\Models\FormField;
use App\Models\Pelayanan;
use Illuminate\Database\Seeder;

class FormFieldSeeder extends Seeder
{
    private array $baseFields = [
        [
            'name' => 'nik',
            'label' => 'Nomor Induk Kependudukan (NIK)',
            'type' => 'number',
            'required' => true,
            'order' => 1,
            'validation_rules' => ['digits:16'],
        ],
        [
            'name' => 'nama_lengkap',
            'label' => 'Nama Lengkap',
            'type' => 'text',
            'required' => true,
            'order' => 2,
        ],
        [
            'name' => 'tempat_lahir',
            'label' => 'Tempat Lahir',
            'type' => 'text',
            'required' => true,
            'order' => 3,
        ],
        [
            'name' => 'tanggal_lahir',
            'label' => 'Tanggal Lahir',
            'type' => 'date',
            'required' => true,
            'order' => 4,
        ],
        [
            'name' => 'jenis_kelamin',
            'label' => 'Jenis Kelamin',
            'type' => 'select',
            'required' => true,
            'options' => ['Laki-laki', 'Perempuan'],
            'order' => 5,
        ],
        [
            'name' => 'alamat',
            'label' => 'Alamat',
            'type' => 'textarea',
            'required' => true,
            'order' => 6,
        ],
        [
            'name' => 'no_hp',
            'label' => 'Nomor Handphone',
            'type' => 'tel',
            'required' => false,
            'order' => 7,
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'required' => false,
            'order' => 8,
        ],
        [
            'name' => 'foto_ktp',
            'label' => 'Foto KTP',
            'type' => 'image',
            'required' => false,
            'options' => [
                'max_size' => 2048,
                'mimes' => ['jpg', 'jpeg', 'png'],
                'dimensions' => [
                    'min_width' => 200,
                    'min_height' => 200,
                ],
            ],
            'order' => 9,
        ],
        [
            'name' => 'keterangan',
            'label' => 'Keterangan Tambahan',
            'type' => 'textarea',
            'required' => false,
            'order' => 10,
        ],
    ];

    public function run(): void
    {
        $pelayanans = Pelayanan::all();

        foreach ($pelayanans as $pelayanan) {
            foreach ($this->baseFields as $field) {
                FormField::firstOrCreate(
                    ['pelayanan_id' => $pelayanan->id, 'name' => $field['name']],
                    $field,
                );
            }
        }
    }
}
