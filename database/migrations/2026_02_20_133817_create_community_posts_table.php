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
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            // Menyambungkan postingan ke user yang membuatnya
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kategori pilihan dari dropdown form nanti
            $table->string('category'); 
            
            // Judul dan Isi postingan
            $table->string('title');
            $table->text('content');
            
            // Untuk menghitung jumlah likes dan komentar nantinya
            $table->integer('likes_count')->default(0);
            $table->integer('views')->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};
