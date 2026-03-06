<?php

use App\Http\Controllers\ApprovalsController;
use App\Http\Controllers\AccountableOfficerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\FundClusterController;
use App\Http\Controllers\IssuanceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SignatoryController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhiteLabelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inventory/track/{token}', [InventoryController::class, 'track'])->name('inventory.track');

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
        Route::get('/breakdown', [ReportsController::class, 'breakdown'])->name('breakdown');
        Route::get('/regspi', [ReportsController::class, 'regspi'])->name('regspi');
        Route::get('/logs', [ReportsController::class, 'logs'])->name('logs');
    });

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::prefix('signatories')->name('signatories.')->group(function (): void {
        Route::get('/', [SignatoryController::class, 'index'])->name('index');
        Route::get('/create', [SignatoryController::class, 'create'])->name('create');
        Route::post('/', [SignatoryController::class, 'store'])->name('store');
        Route::get('/{signatory}/edit', [SignatoryController::class, 'edit'])->name('edit');
        Route::put('/{signatory}', [SignatoryController::class, 'update'])->name('update');
        Route::delete('/{signatory}', [SignatoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('fund-clusters')->name('fund-clusters.')->group(function (): void {
        Route::get('/', [FundClusterController::class, 'index'])->name('index');
        Route::get('/create', [FundClusterController::class, 'create'])->name('create');
        Route::post('/', [FundClusterController::class, 'store'])->name('store');
        Route::get('/{fundCluster}/edit', [FundClusterController::class, 'edit'])->name('edit');
        Route::put('/{fundCluster}', [FundClusterController::class, 'update'])->name('update');
        Route::delete('/{fundCluster}', [FundClusterController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('offices')->name('offices.')->group(function (): void {
        Route::get('/', [OfficeController::class, 'index'])->name('index');
        Route::post('/', [OfficeController::class, 'store'])->name('store');
        Route::delete('/{office}', [OfficeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('accountable-officers')->name('accountable-officers.')->group(function (): void {
        Route::get('/', [AccountableOfficerController::class, 'index'])->name('index');
        Route::post('/', [AccountableOfficerController::class, 'store'])->name('store');
        Route::delete('/{employee}', [AccountableOfficerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('items')->name('items.')->group(function (): void {
        Route::get('/search', [ItemController::class, 'search'])->name('search');
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}/print-qr', [ItemController::class, 'printQr'])->name('print_qr');
        Route::get('/{item}', [ItemController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('inventory')->name('inventory.')->group(function (): void {
        Route::get('/search', [InventoryController::class, 'search'])->name('search');
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('/print', [InventoryController::class, 'print'])->name('print');
        Route::get('/{inventory}', [InventoryController::class, 'show'])->name('show');
    });

    Route::middleware('role:super_admin,system_admin')->prefix('users')->name('users.')->group(function (): void {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::put('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        Route::put('/{user}/status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });

    Route::middleware('role:super_admin')->prefix('white-label')->name('white-label.')->group(function (): void {
        Route::get('/', [WhiteLabelController::class, 'edit'])->name('edit');
        Route::put('/', [WhiteLabelController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
