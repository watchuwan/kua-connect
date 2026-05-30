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
            'options' => null,
            'placeholder' => 'Masukkan 16 digit NIK',
            'help_text' => 'Pastikan NIK sesuai dengan KTP',
            'order' => 1,
        ],
        [
            'name' => 'nama_lengkap',
            'label' => 'Nama Lengkap',
            'type' => 'text',
            'required' => true,
            'options' => null,
            'placeholder' => 'Masukkan nama lengkap',
            'help_text' => null,
            'order' => 2,
        ],
        [
            'name' => 'tempat_lahir',
            'label' => 'Tempat Lahir',
            'type' => 'text',
            'required' => true,
            'options' => null,
            'placeholder' => 'Kota/Kabupaten tempat lahir',
            'help_text' => null,
            'order' => 3,
        ],
        [
            'name' => 'tanggal_lahir',
            'label' => 'Tanggal Lahir',
            'type' => 'date',
            'required' => true,
            'options' => null,
            'placeholder' => null,
            'help_text' => null,
            'order' => 4,
        ],
        [
            'name' => 'jenis_kelamin',
            'label' => 'Jenis Kelamin',
            'type' => 'select',
            'required' => true,
            'options' => ['Laki-laki', 'Perempuan'],
            'placeholder' => 'Pilih jenis kelamin',
            'help_text' => null,
            'order' => 5,
        ],
        [
            'name' => 'alamat',
            'label' => 'Alamat',
            'type' => 'textarea',
            'required' => true,
            'options' => null,
            'placeholder' => 'Masukkan alamat lengkap',
            'help_text' => null,
            'order' => 6,
        ],
        [
            'name' => 'no_hp',
            'label' => 'Nomor Handphone',
            'type' => 'tel',
            'required' => false,
            'options' => null,
            'placeholder' => '08xxxxxxxxxx',
            'help_text' => null,
            'order' => 7,
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'required' => false,
            'options' => null,
            'placeholder' => 'contoh@email.com',
            'help_text' => null,
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
            'placeholder' => null,
            'help_text' => 'Format JPG/PNG, maks 2MB, minimal 200x200px',
            'order' => 9,
        ],
        [
            'name' => 'keterangan',
            'label' => 'Keterangan Tambahan',
            'type' => 'textarea',
            'required' => false,
            'options' => null,
            'placeholder' => 'Informasi tambahan (opsional)',
            'help_text' => null,
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
                    array_merge($field, ['active' => true]),
                );
            }
        }
    }
}
