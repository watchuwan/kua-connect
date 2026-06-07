<?php

namespace Database\Seeders;

use App\Models\Penghulu;
use Illuminate\Database\Seeder;

class PenghuluSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'H. Ahmad Fauzi, S.Ag', 'nip' => '197001012005011001', 'no_hp' => '081234567890'],
            ['nama' => 'H. M. Lutfi Hakim, S.Ag', 'nip' => '197502022005011002', 'no_hp' => '081234567891'],
            ['nama' => 'Drs. H. Mahfudz Ridwan', 'nip' => '196803032005011003', 'no_hp' => '081234567892'],
        ];

        foreach ($data as $item) {
            Penghulu::firstOrCreate(
                ['nip' => $item['nip']],
                $item,
            );
        }
    }
}
