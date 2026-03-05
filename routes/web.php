<?php

use App\Http\Controllers\ApprovalsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\IssuanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('issuance')->name('issuance.')->group(function (): void {
        Route::get('/', [IssuanceController::class, 'index'])->name('index');
        Route::get('/create', [IssuanceController::class, 'create'])->name('create');
        Route::post('/', [IssuanceController::class, 'store'])->name('store');
        Route::get('/{issuance}', [IssuanceController::class, 'show'])->name('show');
        Route::post('/{issuance}/submit', [IssuanceController::class, 'submit'])->name('submit');
        Route::get('/{issuance}/print/{template}', [IssuanceController::class, 'print'])->name('print');
    });

    Route::prefix('transfer')->name('transfer.')->group(function (): void {
        Route::get('/', [TransferController::class, 'index'])->name('index');
        Route::get('/create', [TransferController::class, 'create'])->name('create');
        Route::post('/', [TransferController::class, 'store'])->name('store');
        Route::get('/{transfer}', [TransferController::class, 'show'])->name('show');
        Route::post('/{transfer}/submit', [TransferController::class, 'submit'])->name('submit');
        Route::get('/{transfer}/print/{template}', [TransferController::class, 'print'])->name('print');
    });

    Route::prefix('disposal')->name('disposal.')->group(function (): void {
        Route::get('/', [DisposalController::class, 'index'])->name('index');
        Route::get('/create', [DisposalController::class, 'create'])->name('create');
        Route::post('/', [DisposalController::class, 'store'])->name('store');
        Route::get('/{disposal}', [DisposalController::class, 'show'])->name('show');
        Route::post('/{disposal}/submit', [DisposalController::class, 'submit'])->name('submit');
        Route::get('/{disposal}/print/{template}', [DisposalController::class, 'print'])->name('print');
    });

    Route::prefix('approvals')->name('approvals.')->group(function (): void {
        Route::get('/', [ApprovalsController::class, 'index'])->name('index');
        Route::post('/{approval}/approve', [ApprovalsController::class, 'approve'])->name('approve');
        Route::post('/{approval}/return', [ApprovalsController::class, 'return'])->name('return');
    });

    Route::prefix('reports')->name('reports.')->group(function (): void {
        Route::get('/', [ReportsController::class, 'index'])->name('index');
        Route::get('/ppe-count', [ReportsController::class, 'ppeCount'])->name('ppe_count');
        Route::get('/semi-count', [ReportsController::class, 'semiCount'])->name('semi_count');
        Route::get('/regspi', [ReportsController::class, 'regspi'])->name('regspi');
        Route::get('/logs', [ReportsController::class, 'logs'])->name('logs');
    });
});

require __DIR__.'/auth.php';
