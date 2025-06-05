<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Medicines\Index;
use App\Livewire\Sales\Pos;
use App\Livewire\Sales\History;
use App\Livewire\Reports\Index as ReportsIndex;
use App\Livewire\Dashboard; // ⬅️ Add this line
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ExportController;

Route::get('/', Dashboard::class)->middleware(['auth'])->name('dashboard'); // ⬅️ Updated line

// ✅ Pages
Route::get('/medicines', Index::class)->middleware('auth')->name('medicines');
Route::get('/sales', Pos::class)->middleware('auth')->name('sales');
Route::get('/sales/history', History::class)->middleware('auth')->name('sales.history');
Route::get('/reports', ReportsIndex::class)->middleware('auth')->name('reports.index');

// ✅ Receipt
Route::get('/sales/{sale}/receipt/download', [ReceiptController::class, 'download'])
    ->middleware('auth')->name('sales.receipt.download');

Route::get('/sales/{sale}/receipt/print', [ReceiptController::class, 'print'])
    ->middleware('auth')->name('sales.receipt.print');

// ✅ Exports
Route::get('/sales/export/pdf', [ExportController::class, 'exportPdf'])
    ->middleware('auth')->name('sales.export.pdf');

Route::get('/sales/export/excel', [ExportController::class, 'exportExcel'])
    ->middleware('auth')->name('sales.export.excel');

// ✅ Volt settings
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
