<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek jika kolom 'links' ada, maka hapus
        if (Schema::hasColumn('scripts', 'links')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->dropColumn('links');
            });
        }
    }

    public function down(): void
    {
        // Fitur rollback jika diperlukan
        if (!Schema::hasColumn('scripts', 'links')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->string('links')->nullable();
            });
        }
    }
};