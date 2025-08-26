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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->foreignId('juri_id')->constrained('users')->onDelete('cascade');

            // !! SEMUA KOLOM PENILAIAN DIDEFINISIKAN DI SINI !!
            $table->json('skor_gk_detail')->nullable();
            $table->decimal('total_skor_gk', 5, 2)->nullable();
            $table->text('catatan_juri_gk')->nullable();
            
            $table->json('skor_bi_detail')->nullable();
            $table->decimal('total_skor_bi', 5, 2)->nullable();
            $table->text('catatan_juri_bi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
