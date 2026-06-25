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
        Schema::create('common_section_cta_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('common_section_id')->constrained('page_common_sections')->onDelete('cascade');
            $table->string('button_name');
            $table->foreignId('page_id')->constrained('pages');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_section_cta_buttons');
    }
};
