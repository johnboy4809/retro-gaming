<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arcade = \App\Models\Platform::where('slug', 'arcade')->first();
        if ($arcade) {
            \App\Models\SubPlatform::updateOrCreate(['platform_id' => $arcade->id, 'slug' => 'mame'], [
                'name' => 'MAME',
                'order_index' => 1,
            ]);
            \App\Models\SubPlatform::updateOrCreate(['platform_id' => $arcade->id, 'slug' => 'fbneo'], [
                'name' => 'FBNeo',
                'order_index' => 2,
            ]);
            \App\Models\SubPlatform::updateOrCreate(['platform_id' => $arcade->id, 'slug' => 'chd'], [
                'name' => 'CHDs',
                'order_index' => 3,
            ]);
        }

        $console = \App\Models\Platform::where('slug', 'console')->first();
        if ($console) {
            \App\Models\SubPlatform::updateOrCreate(['platform_id' => $console->id, 'slug' => 'snes'], [
                'name' => 'SNES',
                'order_index' => 1,
            ]);
        }
    }
}
