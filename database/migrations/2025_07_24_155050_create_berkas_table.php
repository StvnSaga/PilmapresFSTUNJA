<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');

            $table->string('nama_berkas'); // Cth: "Juara 1 Lomba Karya Tulis Ilmiah Nasional"
            $table->string('jenis_berkas'); // Cth: 'KTP', 'KRS', 'CU' (Capaian Unggulan)
            $table->string('tingkat')->nullable(); // Cth: 'Nasional', 'Internasional'
            $table->string('path_file'); // Lokasi file disimpan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas');
    }
};
