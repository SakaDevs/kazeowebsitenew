<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu, kalau kolom 'views' BELUM ADA, baru dibikin.
        // Kalau sudah ada, lewati saja biar nggak error.
        if (!Schema::hasColumn('scripts', 'views')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->unsignedBigInteger('views')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('scripts', 'views')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->dropColumn('views');
            });
        }
    }
};