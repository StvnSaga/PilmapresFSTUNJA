<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_jejaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_seleksi_id')->constrained('tahun_seleksis');
            $table->foreignId('peserta_id')->constrained('pesertas');
            $table->integer('peringkat');
            $table->text('deskripsi_singkat')->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_jejaks');
    }
};
