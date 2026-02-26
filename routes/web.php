<?php

use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CashAdvanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cash Advances
    Route::prefix('cash-advances')->name('cash-advances.')->group(function () {
        Route::get('/', [CashAdvanceController::class, 'index'])->name('index');
        Route::get('/create', [CashAdvanceController::class, 'create'])->name('create');
        Route::post('/', [CashAdvanceController::class, 'store'])->name('store');
        Route::get('/{cashAdvance}', [CashAdvanceController::class, 'show'])->name('show');
        Route::post('/{cashAdvance}/submit', [CashAdvanceController::class, 'submit'])->name('submit');
        Route::post('/{cashAdvance}/approve', [CashAdvanceController::class, 'approve'])->name('approve');
        Route::post('/{cashAdvance}/reject', [CashAdvanceController::class, 'reject'])->name('reject');
    });

    // Allowances
    Route::prefix('allowances')->name('allowances.')->group(function () {
        Route::get('/', [AllowanceController::class, 'index'])->name('index');
        Route::get('/create', [AllowanceController::class, 'create'])->name('create');
        Route::post('/', [AllowanceController::class, 'store'])->name('store');
        Route::get('/{allowance}', [AllowanceController::class, 'show'])->name('show');
        Route::post('/{allowance}/submit', [AllowanceController::class, 'submit'])->name('submit');
        Route::post('/{allowance}/approve', [AllowanceController::class, 'approve'])->name('approve');
        Route::post('/{allowance}/reject', [AllowanceController::class, 'reject'])->name('reject');
    });

    // Reimbursements
    Route::prefix('reimbursements')->name('reimbursements.')->group(function () {
        Route::get('/', [ReimbursementController::class, 'index'])->name('index');
        Route::get('/create', [ReimbursementController::class, 'create'])->name('create');
        Route::post('/', [ReimbursementController::class, 'store'])->name('store');
        Route::get('/{reimbursement}', [ReimbursementController::class, 'show'])->name('show');
        Route::post('/{reimbursement}/submit', [ReimbursementController::class, 'submit'])->name('submit');
        Route::post('/{reimbursement}/approve', [ReimbursementController::class, 'approve'])->name('approve');
        Route::post('/{reimbursement}/reject', [ReimbursementController::class, 'reject'])->name('reject');
    });

    // Attendance
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/create', [AttendanceController::class, 'create'])->name('create');
        Route::post('/', [AttendanceController::class, 'store'])->name('store');
        Route::delete('/{attendance}', [AttendanceController::class, 'destroy'])->name('destroy');
    });

    // Projects (Admin & PIC only)
    Route::middleware('role:admin,pic_project')->group(function () {
        Route::resource('projects', ProjectController::class)->except(['show']);
    });

    // Reports (Admin & Finance only)
    Route::middleware('role:admin,finance')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });
});

require __DIR__.'/auth.php';
