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
            ['key' => 'nama_portal', 'value' => 'KUA Connect', 'group' => 'general'],
            ['key' => 'deskripsi_portal', 'value' => 'Portal Pelayanan Publik KUA Kecamatan', 'group' => 'general'],
            ['key' => 'logo', 'value' => '', 'group' => 'general'],
            ['key' => 'hero_image', 'value' => '', 'group' => 'general'],

            // Contact
            ['key' => 'alamat', 'value' => 'Kantor Urusan Agama Kecamatan, Banten', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'admin@kua-connect.go.id', 'group' => 'contact'],
            ['key' => 'telepon', 'value' => '(021) 1234-5678', 'group' => 'contact'],

            // Social Media
            ['key' => 'facebook', 'value' => 'https://facebook.com/kua-connect', 'group' => 'social'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/kua-connect', 'group' => 'social'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/kua-connect', 'group' => 'social'],
            ['key' => 'youtube', 'value' => 'https://youtube.com/@kua-connect', 'group' => 'social'],

            // Important Links
            ['key' => 'link_pemkab', 'value' => 'https://www.kemenag.go.id', 'group' => 'links'],
            ['key' => 'link_satu_data_nasional', 'value' => 'https://satudata.go.id', 'group' => 'links'],
            ['key' => 'link_bappenas', 'value' => 'https://www.bappenas.go.id', 'group' => 'links'],
            ['key' => 'link_bps', 'value' => 'https://www.bps.go.id', 'group' => 'links'],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
