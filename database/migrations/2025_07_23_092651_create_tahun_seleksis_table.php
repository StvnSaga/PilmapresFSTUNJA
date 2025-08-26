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
        Schema::create('tahun_seleksis', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            
            // !! PASTIKAN KOLOM STATUS ADA DI SINI !!
            $table->string('status')->default('pendaftaran');
            
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_seleksis');
    }
};
