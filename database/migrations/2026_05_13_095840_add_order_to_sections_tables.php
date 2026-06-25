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
        Schema::table('page_common_sections', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('status');
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_common_sections', function (Blueprint $table) {
            $table->dropColumn('order');
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
