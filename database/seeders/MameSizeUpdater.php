<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MameSizeUpdater extends Seeder
{
    /**
     * Run the database seeds to update MAME ROM sizes.
     */
    public function run(): void
    {
        $csvPath = base_path('mame-size.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("mame-size.csv not found at {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');

        // Skip header row: Rom,Size (MB)
        fgetcsv($file);

        $this->command->info('Updating MAME ROM sizes from CSV...');

        DB::beginTransaction();

        $count = 0;
        $updated = 0;
        
        while (($row = fgetcsv($file)) !== false) {
            $rom = trim($row[0] ?? '');
            $size = trim($row[1] ?? '');

            if ($rom === '' || !is_numeric($size)) {
                continue;
            }

            // Update size in the database for the matching ROM
            $affected = DB::table('mame')
                ->where('rom', $rom)
                ->update(['size' => (float) $size]);

            if ($affected > 0) {
                $updated++;
            }
            
            $count++;
        }

        DB::commit();
        fclose($file);

        $this->command->info("Processed {$count} rows. Updated {$updated} MAME ROM sizes.");
    }
}
