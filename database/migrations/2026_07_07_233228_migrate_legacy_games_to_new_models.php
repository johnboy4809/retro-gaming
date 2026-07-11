<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate MAME games
        $mameSubPlatform = DB::table('sub_platforms')->where('slug', 'mame')->first();
        if ($mameSubPlatform) {
            $mames = DB::table('mame')->get();
            foreach ($mames as $mame) {
                // Determine total size if not already there, but mame table has size
                DB::table('arcade_games')->insertOrIgnore([
                    'sub_platform_id' => $mameSubPlatform->id,
                    'rom' => $mame->rom,
                    'title' => $mame->full_name,
                    'size_mb' => $mame->size ?? 0,
                    'release_date' => $mame->year,
                    'metadata' => json_encode([
                        'manufacturer' => $mame->manufacturer,
                        'driver' => $mame->driver,
                        'romof' => $mame->romof,
                        'cloneof' => $mame->cloneof,
                        'use_bios' => $mame->use_bios,
                        'use_chds' => $mame->use_chds,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate FBNeo games
        $fbneoSubPlatform = DB::table('sub_platforms')->where('slug', 'fbneo')->first();
        if ($fbneoSubPlatform) {
            $fbneos = DB::table('fbneo')->get();
            foreach ($fbneos as $fbneo) {
                // Fbneo relies on mame metadata for title, size, etc.
                $mameData = DB::table('mame')->where('rom', $fbneo->rom)->first();
                DB::table('arcade_games')->insertOrIgnore([
                    'sub_platform_id' => $fbneoSubPlatform->id,
                    'rom' => $fbneo->rom,
                    'title' => $mameData ? $mameData->full_name : $fbneo->rom,
                    'size_mb' => $mameData ? ($mameData->size ?? 0) : 0,
                    'release_date' => $mameData ? $mameData->year : null,
                    'metadata' => json_encode([
                        'manufacturer' => $mameData->manufacturer ?? null,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Migrate SNES games
        $snesSubPlatform = DB::table('sub_platforms')->where('slug', 'snes')->first();
        if ($snesSubPlatform) {
            $snesGames = DB::table('snes')->get();
            foreach ($snesGames as $snes) {
                DB::table('console_games')->insertOrIgnore([
                    'sub_platform_id' => $snesSubPlatform->id,
                    'rom' => $snes->rom,
                    'title' => $snes->rom, // SNES doesn't have a title column in legacy
                    'size_mb' => $snes->size_mb ?? 0,
                    'release_date' => $snes->release_date,
                    'metadata' => json_encode([
                        'region' => $snes->region,
                        'crc32' => $snes->crc32,
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Deleting from the new tables in case of rollback
        $mameSubPlatform = DB::table('sub_platforms')->where('slug', 'mame')->first();
        if ($mameSubPlatform) DB::table('arcade_games')->where('sub_platform_id', $mameSubPlatform->id)->delete();

        $fbneoSubPlatform = DB::table('sub_platforms')->where('slug', 'fbneo')->first();
        if ($fbneoSubPlatform) DB::table('arcade_games')->where('sub_platform_id', $fbneoSubPlatform->id)->delete();

        $snesSubPlatform = DB::table('sub_platforms')->where('slug', 'snes')->first();
        if ($snesSubPlatform) DB::table('console_games')->where('sub_platform_id', $snesSubPlatform->id)->delete();
    }
};
