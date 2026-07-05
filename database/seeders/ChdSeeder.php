<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChdSeeder extends Seeder
{
    /**
     * Seed the chd table from chd.csv in the root folder.
     */
    public function run(): void
    {
        $csvPath = base_path('chd.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("chd.csv not found at {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');

        // Skip header row: Rom,Size
        fgetcsv($file);

        $batch = [];
        $batchSize = 500;
        $now = now();

        while (($row = fgetcsv($file)) !== false) {
            $rom = trim($row[0] ?? '');
            $size = trim($row[1] ?? '');

            if ($rom === '') {
                continue;
            }

            $batch[] = [
                'rom'        => $rom,
                'size'       => is_numeric($size) ? (float) $size : 0.0,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= $batchSize) {
                DB::table('chd')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('chd')->insert($batch);
        }

        fclose($file);

        $this->command->info('CHD ROMs seeded successfully.');
    }
}
