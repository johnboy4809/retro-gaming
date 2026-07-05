<?php

use App\Http\Controllers\MameController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SnesController;

Route::get('/', function () {
    // Select 6 random ROMs to showcase on the landing homepage
    $featuredGames = \App\Models\Mame::inRandomOrder()->take(6)->get();
    return view('home', compact('featuredGames'));
});

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

// Authenticated Admin Group
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [MameController::class, 'index'])->name('admin.dashboard');
    Route::put('/mame/{mame}', [MameController::class, 'update'])->name('admin.mame.update');
    Route::get('/orders', [MameController::class, 'ordersIndex'])->name('admin.orders');
    Route::post('/orders/{order}/status', [MameController::class, 'orderUpdateStatus'])->name('admin.orders.status');
    // Console group
    Route::get('/console', [SnesController::class, 'index'])->name('admin.console');
    Route::get('/console/snes', [SnesController::class, 'index'])->name('admin.snes');
});
