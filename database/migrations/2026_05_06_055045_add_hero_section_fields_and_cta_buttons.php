<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add new fields to hero_sections
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->string('section_title')->nullable()->after('page_id');
            $table->string('heading')->nullable()->after('section_title');
            $table->text('description')->nullable()->after('heading');
            $table->string('video_path')->nullable()->after('description');
            $table->enum('media_type', ['images', 'video'])->default('images')->after('video_path');
        });
    

        // Create CTA buttons table
        Schema::create('hero_cta_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_section_id')->constrained()->cascadeOnDelete();
            $table->string('button_name');
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_cta_buttons');

        Schema::table('hero_slider_images', function (Blueprint $table) {
            $table->string('heading_np')->nullable()->after('heading');
            $table->string('sub_heading_np')->nullable()->after('sub_heading');
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn(['section_title', 'heading', 'description', 'video_path', 'media_type']);
        });
    }
};
