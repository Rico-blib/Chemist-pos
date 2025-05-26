<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Medicines\Index;

Route::get('/', function () {
    return view('dashboard'); // Dashboard view
})->middleware(['auth'])->name('dashboard');

Route::get('/medicines', Index::class)->middleware('auth')->name('medicines');

Route::view('/sales', 'sales')->middleware('auth')->name('sales');
Route::view('/reports', 'reports')->middleware('auth')->name('reports');

// Volt settings routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
