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
    Schema::create('reactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->morphs('reactable'); // Bisa untuk like postingan community atau like comment
        $table->boolean('is_like'); 
        $table->timestamps();

        // Mencegah 1 user like/dislike berkali-kali di post yang sama
        $table->unique(['user_id', 'reactable_id', 'reactable_type']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
