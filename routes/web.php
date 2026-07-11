<?php

use App\Http\Controllers\MameController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ScreenScraperController;
use App\Http\Controllers\SubPlatformController;

Route::get('/', function () {
    // Select 6 random ROMs to showcase on the landing homepage
    $featuredGames = \App\Models\ArcadeGame::inRandomOrder()->take(6)->get();
    return view('home', compact('featuredGames'));
})->name('home');

// Authentication Routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Cart & Checkout Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [CartController::class, 'checkout'])->middleware('auth')->name('checkout.submit');

// Library Routes
Route::post('/library/setup', [LibraryController::class, 'setupSession'])->name('library.setup');
Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
Route::get('/library/arcade-italia/{rom}', [LibraryController::class, 'getArcadeItaliaMetadata'])->name('library.arcade-italia.metadata');
Route::get('/library/screenscraper/{systemId}/{rom}', [ScreenScraperController::class, 'search'])->name('library.screenscraper.search');
Route::get('/screenscraper-proxy', [ScreenScraperController::class, 'proxy'])->name('screenscraper.proxy');

// Authenticated Admin Group
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.master-platform', 'arcade');
    })->name('admin.dashboard');
    
    Route::get('/arcade-italia/{rom}', [\App\Http\Controllers\ArcadeGameController::class, 'getArcadeItaliaMetadata'])->name('admin.arcade-italia.metadata');
    Route::get('/screenscraper/systems', [ScreenScraperController::class, 'getSystems'])->name('admin.screenscraper.systems');
    Route::get('/screenscraper/{systemId}/{rom}', [ScreenScraperController::class, 'search'])->name('admin.screenscraper.search');
    Route::delete('/screenscraper/{systemId}/{rom}/media', [ScreenScraperController::class, 'deleteMedia'])->name('admin.screenscraper.delete-media');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('admin.orders');
    Route::post('/orders/{order}/status', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('admin.orders.status');

    // Master Platforms (Admin Views)
    Route::get('master/{slug}', [\App\Http\Controllers\PlatformController::class, 'show'])->name('admin.master-platform');

    // Sub-Platforms
    Route::resource('sub-platforms', \App\Http\Controllers\SubPlatformController::class)->except(['index']);
    Route::get('games/csv-template', [\App\Http\Controllers\SubPlatformController::class, 'downloadCsvTemplate'])->name('admin.games.csv-template');

    // CHDs Management
    Route::get('chds', [\App\Http\Controllers\ChdController::class, 'index'])->name('admin.chds.index');
    Route::post('chds', [\App\Http\Controllers\ChdController::class, 'store'])->name('admin.chds.store');
    Route::put('chds/{chd}', [\App\Http\Controllers\ChdController::class, 'update'])->name('admin.chds.update');
    Route::delete('chds/{chd}', [\App\Http\Controllers\ChdController::class, 'destroy'])->name('admin.chds.destroy');
    Route::post('chds/import', [\App\Http\Controllers\ChdController::class, 'import'])->name('admin.chds.import');
    Route::get('chds/csv-template', [\App\Http\Controllers\ChdController::class, 'downloadCsvTemplate'])->name('admin.chds.csv-template');

    // Generic Game Routes
    Route::get('arcade/{subPlatform}/games', [App\Http\Controllers\ArcadeGameController::class, 'index'])->name('arcade.games.index');
    Route::post('arcade/{subPlatform}/games/import', [App\Http\Controllers\ArcadeGameController::class, 'import'])->name('arcade.games.import');
    Route::put('arcade/{subPlatform}/games/{game}', [App\Http\Controllers\ArcadeGameController::class, 'update'])->name('arcade.games.update');
    Route::delete('arcade/{subPlatform}/games/{game}', [App\Http\Controllers\ArcadeGameController::class, 'destroy'])->name('arcade.games.destroy');

    Route::get('console/{subPlatform}/games', [App\Http\Controllers\ConsoleGameController::class, 'index'])->name('console.games.index');
    Route::post('console/{subPlatform}/games/import', [App\Http\Controllers\ConsoleGameController::class, 'import'])->name('console.games.import');
    Route::put('console/{subPlatform}/games/{game}', [App\Http\Controllers\ConsoleGameController::class, 'update'])->name('console.games.update');
    Route::delete('console/{subPlatform}/games/{game}', [App\Http\Controllers\ConsoleGameController::class, 'destroy'])->name('console.games.destroy');

    Route::get('handheld/{subPlatform}/games', [App\Http\Controllers\HandheldGameController::class, 'index'])->name('handheld.games.index');
    Route::post('handheld/{subPlatform}/games/import', [App\Http\Controllers\HandheldGameController::class, 'import'])->name('handheld.games.import');
    Route::put('handheld/{subPlatform}/games/{game}', [App\Http\Controllers\HandheldGameController::class, 'update'])->name('handheld.games.update');
    Route::delete('handheld/{subPlatform}/games/{game}', [App\Http\Controllers\HandheldGameController::class, 'destroy'])->name('handheld.games.destroy');

    Route::get('computer/{subPlatform}/games', [App\Http\Controllers\ComputerGameController::class, 'index'])->name('computer.games.index');
    Route::post('computer/{subPlatform}/games/import', [App\Http\Controllers\ComputerGameController::class, 'import'])->name('computer.games.import');
    Route::put('computer/{subPlatform}/games/{game}', [App\Http\Controllers\ComputerGameController::class, 'update'])->name('computer.games.update');
    Route::delete('computer/{subPlatform}/games/{game}', [App\Http\Controllers\ComputerGameController::class, 'destroy'])->name('computer.games.destroy');
});
