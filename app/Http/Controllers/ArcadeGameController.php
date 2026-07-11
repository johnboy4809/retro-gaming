<?php

namespace App\Http\Controllers;

use App\Models\ArcadeGame;

class ArcadeGameController extends BaseGameController
{
    protected function getModelClass(): string
    {
        return ArcadeGame::class;
    }
    public function getArcadeItaliaMetadata($rom, \App\Services\ArcadeItaliaService $service)
    {
        $data = $service->getGameMetadata($rom);
        if (!$data) {
            return response()->json(['error' => 'Metadata not found or API request failed.'], 404);
        }
        return response()->json($data);
    }
}
