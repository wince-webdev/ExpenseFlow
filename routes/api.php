<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController as ApiExpenseController;
use App\Http\Controllers\Api\RevenueController as ApiRevenueController;
use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\CategoryController as ApiCategoryController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard/stats', [ApiDashboardController::class, 'stats']);

    // On ajoute 'api.' devant chaque nom pour éviter le conflit
    Route::apiResource('expenses', ApiExpenseController::class)
         ->names([
             'index'   => 'api.expenses.index',
             'store'   => 'api.expenses.store',
             'show'    => 'api.expenses.show',
             'update'  => 'api.expenses.update',
             'destroy' => 'api.expenses.destroy',
         ]);

    Route::apiResource('revenues', ApiRevenueController::class)
         ->names([
             'index'   => 'api.revenues.index',
             'store'   => 'api.revenues.store',
             'show'    => 'api.revenues.show',
             'update'  => 'api.revenues.update',
             'destroy' => 'api.revenues.destroy',
         ]);

    Route::apiResource('categories', ApiCategoryController::class)
         ->names([
             'index'   => 'api.categories.index',
             'store'   => 'api.categories.store',
             'show'    => 'api.categories.show',
             'update'  => 'api.categories.update',
             'destroy' => 'api.categories.destroy',
         ]);
});