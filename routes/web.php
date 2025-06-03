<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Medicines\Index;
use App\Livewire\Sales\Pos;
use App\Livewire\Sales\History;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ExportController; // ✅ Add this line

Route::get('/', function () {
    return view('dashboard'); // Dashboard view
})->middleware(['auth'])->name('dashboard');

Route::get('/medicines', Index::class)->middleware('auth')->name('medicines');
Route::get('/sales', Pos::class)->middleware('auth')->name('sales');
Route::get('/sales/history', History::class)->middleware('auth')->name('sales.history');

// ✅ Receipt routes
Route::get('/sales/{sale}/receipt/download', [ReceiptController::class, 'download'])
    ->middleware('auth')
    ->name('sales.receipt.download');

Route::get('/sales/{sale}/receipt/print', [ReceiptController::class, 'print'])
    ->middleware('auth')
    ->name('sales.receipt.print');

// ✅ Export routes
Route::get('/sales/export/pdf', [ExportController::class, 'exportPdf'])
    ->middleware('auth')
    ->name('sales.export.pdf');

Route::get('/sales/export/excel', [ExportController::class, 'exportExcel'])
    ->middleware('auth')
    ->name('sales.export.excel');

Route::view('/reports', 'reports')->middleware('auth')->name('reports');

// ✅ Volt settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
