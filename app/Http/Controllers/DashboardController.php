<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Auth::user() = l'utilisateur actuellement connecté
        $user = Auth::user();

        // Carbon = bibliothèque de dates de Laravel (comme DateTime PHP)
        $debutMois = now()->startOfMonth();
        $finMois   = now()->endOfMonth();

        // TOTAL DÉPENSES DU MOIS
        $totalDepensesMois = Expense::whereBetween('expense_date', [$debutMois, $finMois])
                                    ->where('status', 'approved')
                                    ->sum('amount');

        // TOTAL RECETTES DU MOIS
        $totalRecettesMois = Revenue::whereBetween('revenue_date', [$debutMois, $finMois])
                                    ->sum('amount');

        // BÉNÉFICE NET = Recettes - Dépenses
        $beneficeNet = $totalRecettesMois - $totalDepensesMois;

        // DÉPENSES EN ATTENTE (status = pending)
        $depensesEnAttente = Expense::where('status', 'pending')->count();

        // DERNIÈRES DÉPENSES (5 les plus récentes))
        $dernieresDepenses = Expense::with(['category', 'user'])
                                    ->latest()  // ORDER BY created_at DESC
                                    ->limit(5)
                                    ->get();

        // DERNIÈRES RECETTES
        $dernieresRecettes = Revenue::with(['category', 'user'])
                                    ->latest()
                                    ->limit(5)
                                    ->get();

        // DONNÉES POUR LE GRAPHIQUE (12 derniers mois)
        // On calcule mois par mois les totaux
        $graphiqueDepenses = [];
        $graphiqueRecettes = [];
        $graphiqueMois     = [];

        for ($i = 11; $i >= 0; $i--) {
            // now()->subMonths($i) = il y a $i mois
            $mois = now()->subMonths($i);

            $graphiqueMois[] = $mois->translatedFormat('M Y'); // ex: "Mai 2026"

            $graphiqueDepenses[] = Expense::whereYear('expense_date', $mois->year)
                                          ->whereMonth('expense_date', $mois->month)
                                          ->where('status', 'approved')
                                          ->sum('amount');

            $graphiqueRecettes[] = Revenue::whereYear('revenue_date', $mois->year)
                                          ->whereMonth('revenue_date', $mois->month)
                                          ->sum('amount');
        }

        return view('dashboard', compact(
            'totalDepensesMois',
            'totalRecettesMois',
            'beneficeNet',
            'depensesEnAttente',
            'dernieresDepenses',
            'dernieresRecettes',
            'graphiqueMois',
            'graphiqueDepenses',
            'graphiqueRecettes',
            'user'
        ));
        
    }
}