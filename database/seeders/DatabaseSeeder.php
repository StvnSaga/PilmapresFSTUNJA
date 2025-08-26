<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Perintah ini berarti: "Tolong jalankan semua seeder yang terdaftar di dalam array ini."
        $this->call([
            UserSeeder::class,
            // Jika nanti Anda punya seeder lain (misal: TahunSeleksiSeeder::class),
            // Anda tinggal menambahkannya di sini.
        ]);
    }
}
