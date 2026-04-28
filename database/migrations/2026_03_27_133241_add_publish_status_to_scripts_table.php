<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Cek dan tambah kolom 'status' JIKA belum ada
        if (!Schema::hasColumn('scripts', 'status')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->string('status')->default('published');
            });
        }

        // Cek dan tambah kolom 'published_at' JIKA belum ada
        if (!Schema::hasColumn('scripts', 'published_at')) {
            Schema::table('scripts', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('scripts', function (Blueprint $table) {
            if (Schema::hasColumn('scripts', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('scripts', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });
    }
};
