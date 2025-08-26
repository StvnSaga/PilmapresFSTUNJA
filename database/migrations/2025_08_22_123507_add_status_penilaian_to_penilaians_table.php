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
            $table->string('status_penilaian_gk')->default('draft')->after('catatan_bi_detail');
            $table->string('status_penilaian_bi')->default('draft')->after('status_penilaian_gk');
        });
    }

    public function down(): void
    {
        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropColumn(['status_penilaian_gk', 'status_penilaian_bi']);
        });
    }
};
