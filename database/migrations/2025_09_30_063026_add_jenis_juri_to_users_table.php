<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom baru untuk menyimpan spesialisasi juri (GK atau BI).
            // Dibuat nullable karena tidak semua user (seperti admin/panitia) adalah juri.
            $table->string('jenis_juri')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jenis_juri');
        });
    }
};
