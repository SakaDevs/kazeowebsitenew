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
        Schema::create('replace_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('replace_template_id')->constrained('replace_templates')->cascadeOnDelete();
            $table->string('replace_text'); // Contoh: "Default", "Basic", "Starlight"
            $table->string('image_type')->default('none'); 
            $table->string('image')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
