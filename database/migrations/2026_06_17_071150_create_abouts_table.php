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
        $table->string('breadcrumb_title')->nullable();
        $table->text('breadcrumb_description')->nullable();
        $table->string('breadcrumb_image')->nullable();
        $table->string('about_title')->nullable();
        $table->longText('about_description')->nullable();
        $table->string('about_image')->nullable();
         $table->enum('status', ['draft', 'published']);
        $table->year('established_year')->nullable();
        $table->text('established_description')->nullable();
        $table->string('location')->nullable();
        $table->text('location_description')->nullable();
        $table->string('team_title')->nullable();
        $table->text('team_description')->nullable();
        $table->string('team_image')->nullable();
        $table->string('facilities_title')->nullable();
        $table->json('facilities')->nullable();
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