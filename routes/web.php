<?php

use App\Livewire\CategoryManager;
use App\Livewire\MenuManager;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/categories/create', [CategoryManager::class, 'create'])->name('categories.create');
    Route::get('/categories/{category}/edit', [CategoryManager::class, 'edit'])->name('categories.edit');
    Route::get('/categories/{category}', [CategoryManager::class, 'show'])->name('categories.show');
    Route::delete('/categories/{category}', [CategoryManager::class, 'delete'])->name('categories.delete');

    Route::get('/menus', MenuManager::class)->name('menus');
    Route::get('/menus/create', [MenuManager::class, 'create'])->name('menus.create');
    Route::get('/menus/{menu}/edit', [MenuManager::class, 'edit'])->name('menus.edit');
    Route::get('/menus/{menu}', [MenuManager::class, 'show'])->name('menus.show');
    Route::delete('/menus/{menu}', [MenuManager::class, 'delete'])->name('menus.delete');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
