<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['console_games', 'computer_games', 'handheld_games', 'arcade_games'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('region')->nullable()->after('release_date');
                $table->string('crc32')->nullable()->after('region');
            });

            // Backfill from metadata JSON
            // Extract region
            DB::statement("
                UPDATE {$tableName} 
                SET region = JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.region')) 
                WHERE JSON_EXTRACT(metadata, '$.region') IS NOT NULL
            ");

            // Extract crc32
            DB::statement("
                UPDATE {$tableName} 
                SET crc32 = JSON_UNQUOTE(JSON_EXTRACT(metadata, '$.crc32')) 
                WHERE JSON_EXTRACT(metadata, '$.crc32') IS NOT NULL
            ");
            
            // Note: We leave the values in the JSON metadata intact just to be safe and avoid complicated JSON deletion queries
        }
    }

    public function down(): void
    {
        $tables = ['console_games', 'computer_games', 'handheld_games', 'arcade_games'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['region', 'crc32']);
            });
        }
    }
};
