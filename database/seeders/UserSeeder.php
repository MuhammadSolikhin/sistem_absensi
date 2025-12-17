<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun Super Admin
        User::create([
            'name' => 'Admin DKM',
            'email' => 'admin@masjid.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Buat akun Pengurus (Contoh)
        User::create([
            'name' => 'Pak Haji Ketua',
            'email' => 'ketua@masjid.com',
            'password' => Hash::make('password'),
            'role' => 'pengurus',
        ]);
    }
}