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
            $table->renameColumn('headline', 'heading');
            $table->string('sub_heading')->nullable()->after('section_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_common_sections', function (Blueprint $table) {
            $table->dropColumn('sub_heading');
            $table->renameColumn('heading', 'headline');
        });
    }
};
