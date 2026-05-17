<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // L'ORDRE EST TRÈS IMPORTANT à cause des clés étrangères
        // 1. D'abord les rôles (pas de dépendances)
        $this->call(RoleSeeder::class);

        // 2. Ensuite les catégories (pas de dépendances)
        $this->call(CategorySeeder::class);

        // 3. Ensuite les users (dépend des rôles)
        $this->call(UserSeeder::class);

        // 4. Ensuite les dépenses (dépend des users + catégories)
        $this->call(ExpenseSeeder::class);

        // 5. Enfin les recettes (dépend des users + catégories)
        $this->call(RevenueSeeder::class);
    }
}