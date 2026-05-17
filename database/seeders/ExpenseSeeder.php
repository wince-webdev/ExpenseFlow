<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use App\Models\User;
use App\Models\Category;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les users et catégories existants
        $admin    = User::where('email', 'admin@smartexpense.com')->first();
        $employe  = User::where('email', 'employe@smartexpense.com')->first();

        $catLoyer      = Category::where('name', 'Loyer & Bureaux')->first();
        $catCarburant  = Category::where('name', 'Carburant')->first();
        $catSalaires   = Category::where('name', 'Salaires')->first();

        $expenses = [
            [
                'user_id'      => $admin->id,
                'category_id'  => $catLoyer->id,
                'title'        => 'Loyer bureau mai 2026',
                'amount'       => 150000.00,
                'expense_date' => '2026-05-01',
                'status'       => 'approved',
                'notes'        => 'Loyer mensuel du bureau principal',
            ],
            [
                'user_id'      => $admin->id,
                'category_id'  => $catSalaires->id,
                'title'        => 'Salaires équipe mai 2026',
                'amount'       => 850000.00,
                'expense_date' => '2026-05-05',
                'status'       => 'approved',
                'notes'        => 'Paiement salaires mensuels',
            ],
            [
                'user_id'      => $employe->id,
                'category_id'  => $catCarburant->id,
                'title'        => 'Carburant déplacement client',
                'amount'       => 25000.00,
                'expense_date' => '2026-05-10',
                'status'       => 'pending',
                'notes'        => 'Déplacement chez client Cotonou',
            ],
            [
                'user_id'      => $employe->id,
                'category_id'  => $catCarburant->id,
                'title'        => 'Carburant réunion Parakou',
                'amount'       => 45000.00,
                'expense_date' => '2026-05-15',
                'status'       => 'pending',
                'notes'        => null,
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::firstOrCreate(
                [
                    'title'        => $expense['title'],
                    'expense_date' => $expense['expense_date'],
                ],
                $expense
            );
        }

        $this->command->info('✅ Dépenses de test créées !');
    }
}