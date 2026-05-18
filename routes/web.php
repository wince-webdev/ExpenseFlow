<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;


// ROUTE PUBLIQUE — Page d'accueil redirige vers login
Route::get('/', function () {
    // Si déjà connecté → dashboard
    // Sinon → page login
    return redirect()->route('login');
});


// ROUTES PROTÉGÉES — middleware('auth')
// Seuls les utilisateurs CONNECTÉS peuvent accéder

Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD ---
    // GET /dashboard → DashboardController@index
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // --- DÉPENSES ---
    // Route::resource génère automatiquement les 7 routes CRUD
    // GET    /expenses           → index()   (liste)
    // GET    /expenses/create    → create()  (formulaire)
    // POST   /expenses           → store()   (enregistrer)
    // GET    /expenses/{id}      → show()    (détail)
    // GET    /expenses/{id}/edit → edit()    (formulaire modif)
    // PUT    /expenses/{id}      → update()  (mettre à jour)
    // DELETE /expenses/{id}      → destroy() (supprimer)
    Route::resource('expenses', ExpenseController::class);

    // Route supplémentaire : approuver une dépense (admin/comptable)
    Route::patch('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])
         ->name('expenses.approve');

    Route::patch('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])
         ->name('expenses.reject');

    // --- RECETTES ---
    Route::resource('revenues', RevenueController::class);

    // --- CATÉGORIES ---
    Route::resource('categories', CategoryController::class);

    // --- UTILISATEURS (admin seulement) ---
    Route::resource('users', UserController::class);

});

// Routes générées par Breeze (login, register, logout, etc.)
require __DIR__.'/auth.php';