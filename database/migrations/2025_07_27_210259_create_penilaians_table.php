<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->onDelete('cascade');
            $table->foreignId('juri_id')->constrained('users')->onDelete('cascade');

            $table->json('skor_gk_detail')->nullable();
            $table->decimal('total_skor_gk', 5, 2)->nullable();
            $table->text('catatan_juri_gk')->nullable();
            
            $table->json('skor_bi_detail')->nullable();
            $table->decimal('total_skor_bi', 5, 2)->nullable();
            $table->text('catatan_juri_bi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
