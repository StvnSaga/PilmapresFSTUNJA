<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hanya membuat user Admin utama.
        // Akun Panitia dan Juri akan dibuat oleh Admin melalui aplikasi.
        User::firstOrCreate(
            ['email' => 'admin@pilmapres.com'],
            [
                'name' => 'Admin Pilmapres',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }
}
