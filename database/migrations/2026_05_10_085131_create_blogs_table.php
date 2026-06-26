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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('blog_title');
            $table->string('slug')->nullable()->unique();
            $table->text('content');
            $table->string('excerpt', 100);

            $table->string('featured_image')->nullable();
            $table->integer('read_time')->nullable()->comment('Reading time in minutes');
            $table->foreignId('category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->json('keywords')->nullable();
            $table->enum('status', ['draft', 'published']);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('slug');
            $table->index(['status', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};