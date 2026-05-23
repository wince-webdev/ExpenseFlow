<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Rapport PDF des dépenses
    // GET /reports/expenses/pdf
    public function exportExpensesPdf(Request $request)
    {
        // Récupérer les dépenses avec filtres optionnels
        $query = Expense::with(['category', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_debut')) {
            $query->where('expense_date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('expense_date', '<=', $request->date_fin);
        }

        $expenses = $query->latest()->get();

        // Calculs des totaux
        $totalApprouve = $expenses->where('status', 'approved')->sum('amount');
        $totalEnAttente = $expenses->where('status', 'pending')->sum('amount');
        $totalGeneral = $expenses->sum('amount');

        // Pdf::loadView('nom.vue', données)
        // Charge une vue Blade et la convertit en PDF
        $pdf = Pdf::loadView('reports.expenses', compact(
            'expenses',
            'totalApprouve',
            'totalEnAttente',
            'totalGeneral'
        ));

        // setPaper('a4', 'landscape') = format A4 horizontal
        // setPaper('a4', 'portrait')  = format A4 vertical
        $pdf->setPaper('a4', 'landscape');

        // download('nom-fichier.pdf') = télécharge le PDF
        // stream('nom.pdf') = affiche dans le navigateur
        $nomFichier = 'rapport-depenses-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($nomFichier);
    }

    // -------------------------------------------------------
    // Rapport PDF des revenues
    // GET /reports/revenues/pdf
    // -------------------------------------------------------
    public function exportRevenuesPdf(Request $request)
    {
        $query = Revenue::with(['category', 'user']);

        if ($request->filled('date_debut')) {
            $query->where('revenue_date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('revenue_date', '<=', $request->date_fin);
        }

        $revenues = $query->latest()->get();
        $totalGeneral = $revenues->sum('amount');

        $pdf = Pdf::loadView('reports.revenues', compact('revenues', 'totalGeneral'));
        $pdf->setPaper('a4', 'landscape');

        $nomFichier = 'rapport-revenues-' . now()->format('Y-m-d') . '.pdf';
        return $pdf->download($nomFichier);
    }

    // -------------------------------------------------------
    // Rapport PDF mensuel complet (dépenses + revenues)
    // GET /reports/monthly/pdf
    // -------------------------------------------------------
    public function exportMonthlyPdf(Request $request)
    {
        // Mois courant par défaut
        $mois = $request->filled('mois') ? $request->mois : now()->format('Y-m');
        $debut = $mois . '-01';
        $fin = date('Y-m-t', strtotime($debut)); // dernier jour du mois

        $expenses = Expense::with(['category', 'user'])
                           ->whereBetween('expense_date', [$debut, $fin])
                           ->latest()->get();

        $revenues = Revenue::with(['category', 'user'])
                           ->whereBetween('revenue_date', [$debut, $fin])
                           ->latest()->get();

        $totalDepenses = $expenses->where('status', 'approved')->sum('amount');
        $totalRevenues = $revenues->sum('amount');
        $benefice = $totalRevenues - $totalDepenses;

        $pdf = Pdf::loadView('reports.monthly', compact(
            'expenses', 'revenues',
            'totalDepenses', 'totalRevenues',
            'benefice', 'mois'
        ));
        $pdf->setPaper('a4', 'portrait');

        $nomFichier = 'rapport-mensuel-' . $mois . '.pdf';
        return $pdf->download($nomFichier);
    }
}