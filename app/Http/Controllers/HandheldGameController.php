<?php

namespace App\Http\Controllers;

use App\Models\HandheldGame;

class HandheldGameController extends BaseGameController
{
    protected function getModelClass(): string
    {
        return HandheldGame::class;
    }
}
