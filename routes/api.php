<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\RevenueController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\CategoryController;

// ROUTES PUBLIQUES — Sans token
Route::post('/login', [AuthController::class, 'login']);

// ROUTES PROTÉGÉES — Nécessite token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::apiResource('expenses', ExpenseController::class);
    Route::apiResource('revenues', RevenueController::class);
    Route::apiResource('categories', CategoryController::class);
});