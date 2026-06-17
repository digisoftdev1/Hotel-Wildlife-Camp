<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slider_images', function (Blueprint $table) {
            $table->dropColumn('sub_heading');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slider_images', function (Blueprint $table) {
            $table->string('sub_heading')->nullable()->after('heading');
        });
    }
};
