<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;  
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard for authenticated users
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Make dashboard the default route after login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product routes
    Route::resource('products', ProductController::class);
    
    // Sales routes
    Route::resource('sales', SalesController::class);
    Route::get('/export-sales', [SalesController::class, 'export'])->name('export.sales');
    Route::get('/sales/{sale}/print', [SalesController::class, 'print'])->name('sales.print');
    Route::get('/sales/{sale}/export-csv', [SalesController::class, 'exportCsv'])->name('sales.export.csv');
});

require __DIR__.'/auth.php';
