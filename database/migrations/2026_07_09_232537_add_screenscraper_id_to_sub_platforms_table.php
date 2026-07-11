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
        Schema::table('sub_platforms', function (Blueprint $table) {
            $table->unsignedInteger('screenscraper_id')->nullable()->after('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_platforms', function (Blueprint $table) {
            $table->dropColumn('screenscraper_id');
        });
    }
};
