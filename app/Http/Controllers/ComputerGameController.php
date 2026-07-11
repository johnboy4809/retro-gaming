<?php

namespace App\Http\Controllers;

use App\Models\ComputerGame;

class ComputerGameController extends BaseGameController
{
    protected function getModelClass(): string
    {
        return ComputerGame::class;
    }
}
