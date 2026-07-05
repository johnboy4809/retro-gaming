<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Mame;
use App\Models\ArcadeBoard;
use Illuminate\Support\Facades\Storage;

#[Signature('mame:import {file? : The path to the MAME CSV/TSV file relative to the storage/app folder or absolute path}')]
#[Description('Import MAME games from a Google Sheet CSV/TSV export')]
class ImportMame extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePathInput = $this->argument('file') ?? 'mame.csv';

        // Check if file exists as absolute path, or in storage/app
        if (file_exists($filePathInput)) {
            $filePath = $filePathInput;
        } elseif (Storage::exists($filePathInput)) {
            $filePath = Storage::path($filePathInput);
        } else {
            $this->error("File not found: {$filePathInput}");
            $this->info("Please place your CSV file in storage/app/mame.csv or provide an absolute path.");
            return 1;
        }

        $this->info("Opening file for pre-scan: {$filePath}");

        if (($handle = fopen($filePath, "r")) === FALSE) {
            $this->error("Failed to open file.");
            return 1;
        }

        // Read headers
        $rawHeaders = fgetcsv($handle, 0, ","); // Initial guess: comma
        
        // Auto-detect delimiter: check if tab separator yields more fields
        rewind($handle);
        $tabHeaders = fgetcsv($handle, 0, "\t");
        
        $delimiter = ",";
        $headers = $rawHeaders;
        
        if (count($tabHeaders) > count($rawHeaders)) {
            $delimiter = "\t";
            $headers = $tabHeaders;
            $this->info("Detected tab-separated (TSV) format.");
        } else {
            $this->info("Detected comma-separated (CSV) format.");
        }
        
        // Let's reset pointer to read row 1 as headers
        rewind($handle);
        $headers = fgetcsv($handle, 0, $delimiter);

        // Normalize headers: trim, lowercase, replace space/hyphen with underscore
        $normalizedHeaders = array_map(function ($header) {
            $header = trim($header);
            $header = strtolower($header);
            $header = str_replace([' ', '-'], '_', $header);
            return $header;
        }, $headers);

        // Import arcade boards mapping from storage/app/boards.csv
        $arcadeBoards = [];
        $boardsFilePath = 'storage/app/boards.csv';

        if (file_exists($boardsFilePath)) {
            $this->info("Opening {$boardsFilePath} for pre-scan...");
            if (($boardsHandle = fopen($boardsFilePath, "r")) !== FALSE) {
                // Read headers: Driver,Board
                fgetcsv($boardsHandle, 0, ",");
                while (($row = fgetcsv($boardsHandle, 0, ",")) !== FALSE) {
                    if (isset($row[0]) && isset($row[1])) {
                        $driver = trim($row[0]);
                        $board = trim($row[1]);
                        if ($driver !== '' && $board !== '') {
                            $arcadeBoards[$driver] = $board;
                        }
                    }
                }
                fclose($boardsHandle);
            }
        }
        fclose($handle);

        $this->info("Found " . count($arcadeBoards) . " unique arcade boards in boards.csv.");

        // Bulk insert arcade boards
        if (count($arcadeBoards) > 0) {
            $boardData = [];
            foreach ($arcadeBoards as $driver => $board) {
                $boardData[] = [
                    'driver' => $driver,
                    'board' => $board,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            foreach (array_chunk($boardData, 250) as $chunk) {
                ArcadeBoard::upsert($chunk, ['driver'], ['board', 'updated_at']);
            }
        }

        // Open file again for MAME import
        if (($handle = fopen($filePath, "r")) === FALSE) {
            $this->error("Failed to re-open file.");
            return 1;
        }

        // Skip header row
        fgetcsv($handle, 0, $delimiter);

        // Required primary identification column
        if (!in_array('rom', $normalizedHeaders)) {
            $this->error("CSV must contain a 'rom' (ROM name) column.");
            fclose($handle);
            return 1;
        }

        $count = 0;
        $batch = [];
        $batchSize = 250;

        $booleanFields = [
            'use_bios', 'use_chds'
        ];

        $integerFields = [
            'display_width', 'display_height'
        ];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            // Skip empty rows or header duplicate
            if (empty($row) || count($row) < 1 || !isset($row[0])) {
                continue;
            }

            // Combine headers and row values, ignoring extra/missing cells
            $rowData = [];
            foreach ($normalizedHeaders as $index => $header) {
                $value = isset($row[$index]) ? trim($row[$index]) : null;
                
                // Map boolean fields
                if (in_array($header, $booleanFields)) {
                    $rowData[$header] = in_array(strtolower($value), ['1', 'yes', 'true', 'y']);
                } 
                // Map integer fields
                elseif (in_array($header, $integerFields)) {
                    $rowData[$header] = is_numeric($value) ? (int)$value : null;
                }
                // Standard fields
                else {
                    if ($header === 'driver' && $value !== null) {
                        $value = preg_replace('/\.cpp$/i', '', $value);
                    }
                    $rowData[$header] = $value !== '' ? $value : null;
                }
            }

            // Skip rows without a rom name
            if (empty($rowData['rom'])) {
                continue;
            }

            // Fill missing required columns in model fillable list with defaults if not present in CSV
            $modelFields = [
                'rom', 'full_name', 'driver', 'year', 'manufacturer',
                'romof', 'cloneof', 'use_bios', 'use_chds', 'display_rotate',
                'display_width', 'display_height', 'display_orientation', 'sourcefile'
            ];

            $insertData = [];
            foreach ($modelFields as $field) {
                if (array_key_exists($field, $rowData)) {
                    $insertData[$field] = $rowData[$field];
                } else {
                    // Provide default values if not present
                    if (in_array($field, $booleanFields)) {
                        $insertData[$field] = false;
                    } else {
                        $insertData[$field] = null;
                    }
                }
            }

            // Add timestamps
            $insertData['created_at'] = now();
            $insertData['updated_at'] = now();

            $batch[] = $insertData;
            $count++;

            if (count($batch) >= $batchSize) {
                Mame::upsert($batch, ['rom'], array_keys(array_diff_key($batch[0], ['rom' => 1, 'created_at' => 1])));
                $batch = [];
                $this->info("Imported {$count} records...");
            }
        }

        // Insert remaining
        if (count($batch) > 0) {
            Mame::upsert($batch, ['rom'], array_keys(array_diff_key($batch[0], ['rom' => 1, 'created_at' => 1])));
            $this->info("Imported {$count} records...");
        }

        fclose($handle);
        $this->info("Successfully imported {$count} MAME entries into the database!");
        return 0;
    }
}
