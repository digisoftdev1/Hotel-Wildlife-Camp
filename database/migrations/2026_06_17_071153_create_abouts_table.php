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
    Schema::create('abouts', function (Blueprint $table) {
        $table->id();
                    $table->foreignId('common_section_id')->constrained('page_common_sections')->onDelete('cascade');

        $table->year('established_year')->nullable();
        $table->text('established_description')->nullable();
        $table->string('location')->nullable();
        $table->text('location_description')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};