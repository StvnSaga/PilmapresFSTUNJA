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
        Schema::table('penilaians', function (Blueprint $table) {
            // Menambahkan kolom untuk catatan detail GK
            $table->json('catatan_gk_detail')->nullable()->after('catatan_juri_gk');
            
            // Menambahkan kolom untuk catatan detail BI
            $table->json('catatan_bi_detail')->nullable()->after('catatan_juri_bi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn(['catatan_gk_detail', 'catatan_bi_detail']);
        });
    }
};
