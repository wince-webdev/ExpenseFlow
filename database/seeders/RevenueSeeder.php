<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Revenue;
use App\Models\User;
use App\Models\Category;

class RevenueSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@smartexpense.com')->first();

        $catVentes      = Category::where('name', 'Ventes Produits')->first();
        $catPrestations = Category::where('name', 'Prestations Services')->first();
        $catConsulting  = Category::where('name', 'Consulting')->first();

        $revenues = [
            [
                'user_id'      => $admin->id,
                'category_id'  => $catVentes->id,
                'title'        => 'Vente matériel informatique',
                'amount'       => 350000.00,
                'revenue_date' => '2026-05-03',
                'notes'        => 'Vente 5 ordinateurs portables',
            ],
            [
                'user_id'      => $admin->id,
                'category_id'  => $catPrestations->id,
                'title'        => 'Développement site web client A',
                'amount'       => 500000.00,
                'revenue_date' => '2026-05-08',
                'notes'        => 'Solde final du projet',
            ],
            [
                'user_id'      => $admin->id,
                'category_id'  => $catConsulting->id,
                'title'        => 'Consulting ERP entreprise B',
                'amount'       => 200000.00,
                'revenue_date' => '2026-05-12',
                'notes'        => null,
            ],
        ];

        foreach ($revenues as $revenue) {
            Revenue::firstOrCreate(
                [
                    'title'        => $revenue['title'],
                    'revenue_date' => $revenue['revenue_date'],
                ],
                $revenue
            );
        }

        $this->command->info('✅ Revenues de test créées !');
    }
}