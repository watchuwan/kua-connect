<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ["email" => "admin@example.com"],
            [
                "name" => "Admin",
                "password" => Hash::make("password"),
                "email_verified_at" => now(),
                "is_active" => true,
            ],
        );

        $this->call([
            AppSettingSeeder::class,
            PelayananSeeder::class,
            FormFieldSeeder::class,
            PendaftaranSeeder::class,
            PenghuluSeeder::class,
        ]);
    }
}
