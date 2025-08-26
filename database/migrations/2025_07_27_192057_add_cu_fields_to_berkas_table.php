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
        Schema::table('berkas', function (Blueprint $table) {
            // Kolom untuk menyimpan hasil klasifikasi CU
            $table->string('cu_bidang')->nullable()->after('tingkat');
            $table->string('cu_wujud')->nullable()->after('cu_bidang');
        });
    }

    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            $table->dropColumn(['cu_bidang', 'cu_wujud']);
        });
    }
};
