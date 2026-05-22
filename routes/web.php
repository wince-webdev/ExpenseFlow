<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;



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

    // --- Revenues ---
    Route::resource('revenues', RevenueController::class);

    // --- CATÉGORIES ---
    Route::resource('categories', CategoryController::class);

    // --- UTILISATEURS (admin seulement) ---
//     Route::resource('users', UserController::class);

    // Routes admin seulement
     // middleware('role:admin') = Spatie vérifie le rôle avant d'accéder
     Route::middleware(['auth', 'role:admin'])->group(function () {
     Route::resource('users', UserController::class);
     });

     // Routes auth + tous les rôles
     Route::middleware(['auth'])->group(function () {
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
     Route::resource('expenses', ExpenseController::class);
     Route::patch('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
     Route::patch('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
     Route::resource('revenues', RevenueController::class);
     Route::resource('categories', CategoryController::class);


// Routes rapports PDF
     Route::get('/reports/expenses/pdf', [ReportController::class, 'exportExpensesPdf'])
          ->name('reports.expenses.pdf');
     Route::get('/reports/revenues/pdf', [ReportController::class, 'exportRevenuesPdf'])
          ->name('reports.revenues.pdf');
     Route::get('/reports/monthly/pdf', [ReportController::class, 'exportMonthlyPdf'])
          ->name('reports.monthly.pdf');
     });

});

// Routes générées par Breeze (login, register, logout, etc.)
require __DIR__.'/auth.php';