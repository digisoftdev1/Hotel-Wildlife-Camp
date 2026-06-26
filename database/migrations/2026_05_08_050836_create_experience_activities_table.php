<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('experience_activities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug')->unique();
            $table->string('excerpt', 500);
            $table->string('best_time', 100);
            $table->string('duration', 50);
            $table->string('difficulty_level', 50);
            $table->json('highlights')->nullable();
            $table->text('overview');
            $table->foreignId('category_id')->nullable()->constrained('activity_package_categories')->nullOnDelete();
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_activities');
    }
};