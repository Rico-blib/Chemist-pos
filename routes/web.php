<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Medicines\Index;
use App\Livewire\Sales\Pos;
use App\Livewire\Sales\History;
use App\Livewire\Reports\Index as ReportsIndex;
use App\Livewire\Dashboard;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ExportController;
use App\Http\Middleware\RoleMiddleware;

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('home');

// 🟢 All roles can access dashboard
Route::get('/', Dashboard::class)
    ->middleware(['auth'])
    ->name('dashboard');

// ✅ Admin & Pharmacist: Medicines
Route::get('/medicines', Index::class)
    ->middleware(['auth', RoleMiddleware::class . ':admin,pharmacist'])
    ->name('medicines');

// ✅ Admin & Cashier: POS
Route::get('/sales', Pos::class)
    ->middleware(['auth', RoleMiddleware::class . ':admin,cashier'])
    ->name('sales');

// ✅ Admin only: Sales History
Route::get('/sales/history', History::class)
    ->middleware(['auth', RoleMiddleware::class . ':admin'])
    ->name('sales.history');

// ✅ Admin only: Reports
Route::get('/reports', ReportsIndex::class)
    ->middleware(['auth', RoleMiddleware::class . ':admin'])
    ->name('reports.index');

// ✅ Admin & Cashier: Receipt actions
Route::middleware(['auth', RoleMiddleware::class . ':admin,cashier'])->group(function () {
    Route::get('/sales/{sale}/receipt/download', [ReceiptController::class, 'download'])
        ->name('sales.receipt.download');

    Route::get('/sales/{sale}/receipt/print', [ReceiptController::class, 'print'])
        ->name('sales.receipt.print');
});

// ✅ Admin only: Export actions
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/sales/export/pdf', [ExportController::class, 'exportPdf'])
        ->name('sales.export.pdf');

    Route::get('/sales/export/excel', [ExportController::class, 'exportExcel'])
        ->name('sales.export.excel');
});

// ✅ Volt settings (all roles)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
