<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SnesSeeder extends Seeder
{
    /**
     * Seed the snes table from storage/app/snes.csv
     */
    public function run(): void
    {
        $csvPath = storage_path('app/snes.csv');

        if (!file_exists($csvPath)) {
            $this->command->warn("snes.csv not found at {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');

        // Skip header row
        fgetcsv($file);

        $batch = [];
        $batchSize = 500;
        $now = now();

        while (($row = fgetcsv($file)) !== false) {
            // Columns: Rom, Region, Release Date, Size (MB), CRC32
            $rom = trim($row[0] ?? '');

            // Skip blank ROM names
            if ($rom === '') {
                continue;
            }

            $batch[] = [
                'rom'          => $rom,
                'region'       => trim($row[1] ?? '') ?: null,
                'release_date' => trim($row[2] ?? '') ?: null,
                'size_mb'      => is_numeric(trim($row[3] ?? '')) ? (float) trim($row[3]) : null,
                'crc32'        => trim($row[4] ?? '') ?: null,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('snes')->insert($batch);
                $batch = [];
            }
        }

        // Insert any remaining rows
        if (!empty($batch)) {
            DB::table('snes')->insert($batch);
        }

        fclose($file);

        $this->command->info('SNES ROMs seeded successfully.');
    }
}
