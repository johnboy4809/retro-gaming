<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $data = \ScreenScraper\ScreenScraperClient::getGame([
        'romnom' => 'smw.zip',
        'systemeid' => 4 // SNES
    ]);
    echo json_encode($data, JSON_PRETTY_PRINT);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
