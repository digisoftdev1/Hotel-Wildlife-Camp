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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('duration')->nullable();
            $table->string('grade')->nullable();
            $table->json('includes')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('overview')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');
            $table->json('itinerary')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('activity_package_categories')->onDelete('set null');
            $table->string('status')->default('active');
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
