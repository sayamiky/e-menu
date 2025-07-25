<?php

use App\Livewire\CategoryManager;
use App\Livewire\MenuManager;
use App\Livewire\OrderManager;
use App\Livewire\OrderMenuManager;
use App\Livewire\PaymentManager;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('guest')->get('order-menus', OrderMenuManager::class)->name('order-menus');

Route::middleware(['auth'])->group(function () {
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/menus', MenuManager::class)->name('menus');
    Route::get('/payments', PaymentManager::class)->name('payments');
    Route::get('/orders', OrderManager::class)->name('orders');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
