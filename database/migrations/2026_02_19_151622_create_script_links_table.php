<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cek aman: Kalau tabel belum ada, baru dibikin. 
        // (Biar gak bentrok kalau sebelumnya sudah telanjur terbuat saat error)
        if (!Schema::hasTable('script_links')) {
            Schema::create('script_links', function (Blueprint $table) {
                $table->id();
                $table->foreignId('script_id')->constrained()->cascadeOnDelete();
                $table->string('replace_name');
                $table->string('url');
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // 2. Cek aman: Hapus kolom download_link HANYA jika kolomnya memang masih ada
        if (Schema::hasColumn('scripts', 'download_link')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->dropColumn('download_link');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('script_links');
        
        if (!Schema::hasColumn('scripts', 'download_link')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->string('download_link')->nullable();
            });
        }
    }
};