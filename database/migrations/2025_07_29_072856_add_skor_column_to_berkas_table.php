<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            // Menambahkan kolom skor setelah kolom 'path_file'
            $table->unsignedTinyInteger('skor')->after('path_file')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            $table->dropColumn('skor');
        });
    }
};
