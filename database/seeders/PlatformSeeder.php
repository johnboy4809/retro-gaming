<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            [
                'name' => 'Arcade',
                'slug' => 'arcade',
                'icon' => 'icon-arcade',
                'color' => 'retro-cyan',
                'description' => 'This catalog group contains arcade classics from MAME, FBNeo, and other arcade boards.',
                'order_index' => 1,
            ],
            [
                'name' => 'Console',
                'slug' => 'console',
                'icon' => 'icon-console',
                'color' => 'retro-magenta',
                'description' => 'This catalog group contains home console systems like SNES, Genesis, and PlayStation.',
                'order_index' => 2,
            ],
            [
                'name' => 'Handhelds',
                'slug' => 'handhelds',
                'icon' => 'icon-hand-held',
                'color' => 'retro-purple',
                'description' => 'This catalog group contains portable systems like Game Boy, Game Gear, and PSP.',
                'order_index' => 3,
            ],
            [
                'name' => 'Home Computer',
                'slug' => 'home_computer',
                'icon' => 'icon-home-computer',
                'color' => 'retro-yellow',
                'description' => 'This catalog group contains home computers like Amiga, Commodore 64, and DOS.',
                'order_index' => 4,
            ],
        ];

        foreach ($platforms as $platform) {
            \App\Models\Platform::updateOrCreate(['slug' => $platform['slug']], $platform);
        }
    }
}
