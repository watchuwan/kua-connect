<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'nama_portal', 'value' => 'Tangkab Melayani', 'group' => 'general'],
            ['key' => 'deskripsi_portal', 'value' => 'Portal Pelayanan Publik Kabupaten Tangerang', 'group' => 'general'],
            ['key' => 'logo', 'value' => '', 'group' => 'general'],
            ['key' => 'hero_image', 'value' => '', 'group' => 'general'],

            // Contact
            ['key' => 'alamat', 'value' => 'Komplek Pusat Pemerintahan Kabupaten Tangerang, Tigaraksa, Banten 15720', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'admin@tangerangkab.go.id', 'group' => 'contact'],
            ['key' => 'telepon', 'value' => '(021) 1234-5678', 'group' => 'contact'],

            // Social Media
            ['key' => 'facebook', 'value' => 'https://facebook.com/kabtangerang', 'group' => 'social'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/kabtangerang', 'group' => 'social'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/kabtangerang', 'group' => 'social'],
            ['key' => 'youtube', 'value' => 'https://youtube.com/@kabtangerang', 'group' => 'social'],

            // Important Links
            ['key' => 'link_pemkab', 'value' => 'https://www.tangerangkab.go.id', 'group' => 'links'],
            ['key' => 'link_satu_data_nasional', 'value' => 'https://satudata.go.id', 'group' => 'links'],
            ['key' => 'link_bappenas', 'value' => 'https://www.bappenas.go.id', 'group' => 'links'],
            ['key' => 'link_bps', 'value' => 'https://tangerangkab.bps.go.id', 'group' => 'links'],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
