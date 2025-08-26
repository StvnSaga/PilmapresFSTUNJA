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
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();

            // Kolom ini krusial untuk menghubungkan peserta ke periode
            $table->foreignId('tahun_seleksi_id')->constrained('tahun_seleksis')->onDelete('cascade');

            $table->string('nama_lengkap');
            $table->string('nim');
            $table->string('prodi');
            $table->year('angkatan');
            $table->string('no_hp');
            $table->string('email');
            $table->decimal('ipk', 3, 2)->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            
            // Kolom-kolom skor
            $table->decimal('total_skor_cu', 5, 2)->nullable();
            $table->decimal('total_skor_gk', 5, 2)->nullable();
            $table->decimal('total_skor_bi', 5, 2)->nullable();
            $table->decimal('skor_akhir', 5, 2)->nullable();
            
            $table->timestamps();

            // Membuat unique key dari kombinasi nim dan tahun_seleksi_id
            $table->unique(['nim', 'tahun_seleksi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};
