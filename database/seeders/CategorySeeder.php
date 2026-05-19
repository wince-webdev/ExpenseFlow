<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Catégories pour les DÉPENSES
        $expenseCategories = [
            ['name' => 'Loyer & Bureaux',    'color' => '#EF4444'], // rouge
            ['name' => 'Carburant',           'color' => '#F97316'], // orange
            ['name' => 'Salaires',            'color' => '#EAB308'], // jaune
            ['name' => 'Fournitures',         'color' => '#84CC16'], // vert clair
            ['name' => 'Électricité & Eau',   'color' => '#06B6D4'], // cyan
            ['name' => 'Transport',           'color' => '#8B5CF6'], // violet
            ['name' => 'Communication',       'color' => '#EC4899'], // rose
            ['name' => 'Maintenance',         'color' => '#6B7280'], // gris
        ];

        foreach ($expenseCategories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name'], 'type' => 'expense'],
                ['color' => $cat['color']]
            );
        }

        // Catégories pour les REVENUES
        $revenueCategories = [
            ['name' => 'Ventes Produits',     'color' => '#10B981'], // vert
            ['name' => 'Prestations Services','color' => '#3B82F6'], // bleu
            ['name' => 'Consulting',          'color' => '#6366F1'], // indigo
            ['name' => 'Subventions',         'color' => '#14B8A6'], // teal
            ['name' => 'Remboursements',      'color' => '#F59E0B'], // amber
        ];

        foreach ($revenueCategories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name'], 'type' => 'revenue'],
                ['color' => $cat['color']]
            );
        }

        $this->command->info('✅ Catégories créées avec succès !');
    }
}