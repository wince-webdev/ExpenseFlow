<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'utilisateur ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin@smartexpense.com'],
            [
                'name'     => 'Administrateur',
                'password' => Hash::make('password123'),
            ]
        );
        // Assigner le rôle admin
        $admin->assignRole('admin');

        // Créer un COMPTABLE
        $comptable = User::firstOrCreate(
            ['email' => 'comptable@smartexpense.com'],
            [
                'name'     => 'Jean Comptable',
                'password' => Hash::make('password123'),
            ]
        );
        $comptable->assignRole('comptable');

        // Créer un EMPLOYÉ
        $employe = User::firstOrCreate(
            ['email' => 'employe@smartexpense.com'],
            [
                'name'     => 'Marie Employée',
                'password' => Hash::make('password123'),
            ]
        );
        $employe->assignRole('employe');

        $this->command->info('✅ Utilisateurs créés !');
        $this->command->info('   Admin    → admin@smartexpense.com / password123');
        $this->command->info('   Comptable→ comptable@smartexpense.com / password123');
        $this->command->info('   Employé  → employe@smartexpense.com / password123');
    }
}