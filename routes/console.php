<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\RevenueController;
use App\Http\Controllers\Api\DashboardController;

// ROUTES PUBLIQUES — Pas besoin d'être connecté
Route::post('/login', [AuthController::class, 'login']);

// ROUTES PROTÉGÉES — Nécessite un token Sanctum valide
// middleware('auth:sanctum') = vérifie le token dans le header
// Authorization: Bearer TON_TOKEN
Route::middleware('auth:sanctum')->group(function () {

    // Déconnexion
Route::post('/logout', [AuthController::class, 'logout']);

    // Infos utilisateur connecté
Route::get('/me', [AuthController::class, 'me']);

    // Dashboard stats
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Dépenses
Route::apiResource('expenses', ExpenseController::class);

    // Revenues
Route::apiResource('revenues', RevenueController::class);
});