<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Fbneo;
use Illuminate\Support\Facades\Storage;

#[Signature('fbneo:import {file? : The path to the FBNeo CSV file relative to storage/app or absolute}')]
#[Description('Import FBNeo games from a CSV file')]
class ImportFbneo extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePathInput = $this->argument('file') ?? 'fbneo.csv';
        $storagePath = storage_path('app/' . $filePathInput);

        if (file_exists($filePathInput)) {
            $filePath = $filePathInput;
        } elseif (file_exists($storagePath)) {
            $filePath = $storagePath;
        } elseif (Storage::exists($filePathInput)) {
            $filePath = Storage::path($filePathInput);
        } else {
            $this->error("File not found: {$filePathInput}");
            $this->info("Please place your CSV file in storage/app/fbneo.csv or provide an absolute path.");
            return 1;
        }

        $this->info("Opening file: {$filePath}");

        if (($handle = fopen($filePath, "r")) === FALSE) {
            $this->error("Failed to open file.");
            return 1;
        }

        // Read first row to determine headers
        $headers = fgetcsv($handle, 0, ",");
        if ($headers === FALSE) {
            $this->error("Empty file.");
            fclose($handle);
            return 1;
        }

        // Normalize headers
        $normalizedHeaders = array_map(function ($header) {
            return strtolower(trim($header));
        }, $headers);

        // Find index of 'rom' or 'name'
        $romIndex = array_search('rom', $normalizedHeaders);
        if ($romIndex === FALSE) {
            $romIndex = array_search('name', $normalizedHeaders);
        }

        // If no matching header, treat the first line as data and first column as rom
        if ($romIndex === FALSE) {
            $this->info("No 'rom' or 'name' header found. Treating first row as data and column 0 as rom.");
            $romIndex = 0;
            rewind($handle);
        }

        $count = 0;
        $batch = [];
        $batchSize = 250;

        while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
            if (empty($row) || !isset($row[$romIndex])) {
                continue;
            }

            $rom = trim($row[$romIndex]);
            if ($rom === '') {
                continue;
            }

            $batch[] = [
                'rom' => $rom,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $count++;

            if (count($batch) >= $batchSize) {
                Fbneo::upsert($batch, ['rom'], ['updated_at']);
                $batch = [];
                $this->info("Imported {$count} records...");
            }
        }

        if (count($batch) > 0) {
            Fbneo::upsert($batch, ['rom'], ['updated_at']);
            $this->info("Imported {$count} records...");
        }

        fclose($handle);
        $this->info("Successfully imported {$count} FBNeo entries into the database!");
        return 0;
    }
}
