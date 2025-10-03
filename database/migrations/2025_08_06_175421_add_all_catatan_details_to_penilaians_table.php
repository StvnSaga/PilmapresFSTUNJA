<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->json('catatan_gk_detail')->nullable()->after('catatan_juri_gk');
            $table->json('catatan_bi_detail')->nullable()->after('catatan_juri_bi');
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn(['catatan_gk_detail', 'catatan_bi_detail']);
        });
    }
};
