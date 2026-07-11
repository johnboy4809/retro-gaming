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
        $tables = ['arcade_games', 'console_games', 'computer_games', 'handheld_games'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                // Drop size_mb
                $table->dropColumn('size_mb');
                // Change release_date to DATE
                $table->date('release_date')->nullable()->change();
            });

            // Add size_bytes as bigInteger after title
            Schema::table($tableName, function (Blueprint $table) {
                $table->unsignedBigInteger('size_bytes')->default(0)->after('title');
            });
        }

        // CHD table
        Schema::table('chd', function (Blueprint $table) {
            $table->dropColumn('size');
        });
        Schema::table('chd', function (Blueprint $table) {
            $table->unsignedBigInteger('size_bytes')->default(0)->after('rom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['arcade_games', 'console_games', 'computer_games', 'handheld_games'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('size_bytes');
                $table->string('release_date')->nullable()->change();
            });
            Schema::table($tableName, function (Blueprint $table) {
                $table->decimal('size_mb', 10, 2)->default(0)->after('title');
            });
        }

        // CHD table
        Schema::table('chd', function (Blueprint $table) {
            $table->dropColumn('size_bytes');
        });
        Schema::table('chd', function (Blueprint $table) {
            $table->decimal('size', 10, 2)->default(0)->after('rom');
        });
    }
};
