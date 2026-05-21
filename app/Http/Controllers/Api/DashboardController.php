<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Revenue;

class DashboardController extends Controller
{
    public function stats()
    {
        $debutMois = now()->startOfMonth();
        $finMois   = now()->endOfMonth();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_depenses_mois'  => Expense::whereBetween('expense_date', [$debutMois, $finMois])
                                                  ->where('status', 'approved')
                                                  ->sum('amount'),
                'total_revenues_mois'  => Revenue::whereBetween('revenue_date', [$debutMois, $finMois])
                                                  ->sum('amount'),
                'depenses_en_attente'  => Expense::where('status', 'pending')->count(),
                'total_depenses_global'=> Expense::where('status', 'approved')->sum('amount'),
                'total_revenues_global'=> Revenue::sum('amount'),
            ],
        ]);
    }
}