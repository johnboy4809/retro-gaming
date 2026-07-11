<?php

namespace App\Http\Controllers;

use App\Models\ConsoleGame;

class ConsoleGameController extends BaseGameController
{
    protected function getModelClass(): string
    {
        return ConsoleGame::class;
    }
}
