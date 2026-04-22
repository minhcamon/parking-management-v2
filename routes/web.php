<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MonthlyPassController as AdminMonthlyPassController;
use App\Http\Controllers\Admin\ParkingSiteController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffManagerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Staff\HistoryController as StaffHistoryController;
use App\Http\Controllers\Staff\OperationController;
use App\Http\Controllers\Staff\StaffDashboardController;
use Illuminate\Support\Facades\Route;

// --- Welcome & Auth ---
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Admin Section ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'getDashboard'])->name('dashboard');
    Route::post('/add-card', [AdminDashboardController::class, 'addNewCard'])->name('add-card'); // Keeping from previous code

    // Parking Site (Vehicle Types & Cards)
    Route::get('/parking-site', [ParkingSiteController::class, 'index'])->name('parking-site.index');
    Route::post('/cards', [ParkingSiteController::class, 'store'])->name('cards.store');
    Route::post('/cards/bulk', [ParkingSiteController::class, 'bulkStore'])->name('cards.bulk');
    Route::put('/cards/update/{id}', [ParkingSiteController::class, 'update'])->name('cards.update');

    Route::put('/vehicles/{id}', [ParkingSiteController::class, 'updateVehicle'])->name('vehicles.update');

    // User & Staff Management
    Route::get('/staff', [StaffManagerController::class, 'index'])->name('staff.index');

    // Monthly Passes
    Route::get('/monthly-passes', [AdminMonthlyPassController::class, 'index'])->name('monthly-passes.index');
    Route::post('/monthly-passes', [AdminMonthlyPassController::class, 'store'])->name('monthly-passes.store');

    // Reports (History & Revenue)
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
});

// --- Staff Section ---
Route::prefix('staff')->name('staff.')->group(function () {
    // Dashboard (Check In / Check Out UI)
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Check in - Out
    Route::post('/check-in', [StaffDashboardController::class, 'checkIn'])->name('check-in');
    Route::post('/check-out', [StaffDashboardController::class, 'checkOut'])->name('check-out');

    // Operations (Search, Register Pass)
    Route::get('/operations/search', [OperationController::class, 'search'])->name('operations.search');
    Route::get('/operations/register-pass', [OperationController::class, 'registerPass'])->name('operations.register-pass');

    // History & Transactions (Staff scope)
    Route::get('/history', [StaffHistoryController::class, 'index'])->name('history.index');
});
