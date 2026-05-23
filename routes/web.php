<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
         ->name('dashboard');

    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::patch('/expenses/{expense}/approve', [\App\Http\Controllers\ExpenseController::class, 'approve'])
         ->name('expenses.approve');
    Route::patch('/expenses/{expense}/reject', [\App\Http\Controllers\ExpenseController::class, 'reject'])
         ->name('expenses.reject');

    Route::resource('revenues', \App\Http\Controllers\RevenueController::class);
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);

    Route::get('/reports/expenses/pdf', [\App\Http\Controllers\ReportController::class, 'exportExpensesPdf'])
         ->name('reports.expenses.pdf');
    Route::get('/reports/revenues/pdf', [\App\Http\Controllers\ReportController::class, 'exportRevenuesPdf'])
         ->name('reports.revenues.pdf');
    Route::get('/reports/monthly/pdf', [\App\Http\Controllers\ReportController::class, 'exportMonthlyPdf'])
         ->name('reports.monthly.pdf');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

require __DIR__.'/auth.php';