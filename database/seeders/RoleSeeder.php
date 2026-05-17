<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des rôles/permissions Spatie
        // IMPORTANT : toujours faire ça avant de créer des rôles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer les permissions
        $permissions = [
            // Permissions dépenses
            'view expenses',
            'create expenses',
            'edit expenses',
            'delete expenses',
            'approve expenses',

            // Permissions recettes
            'view revenues',
            'create revenues',
            'edit revenues',
            'delete revenues',

            // Permissions utilisateurs
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Permissions catégories
            'manage categories',

            // Permissions rapports
            'view reports',
            'export reports',
        ];

        // Créer chaque permission en base
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer le rôle ADMIN → a TOUTES les permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Créer le rôle COMPTABLE
        $comptable = Role::firstOrCreate(['name' => 'comptable']);
        $comptable->givePermissionTo([
            'view expenses',
            'create expenses',
            'edit expenses',
            'approve expenses',
            'view revenues',
            'create revenues',
            'edit revenues',
            'view reports',
            'export reports',
        ]);

        // -----------------------------------------------
        // Créer le rôle EMPLOYE
        // -----------------------------------------------
        $employe = Role::firstOrCreate(['name' => 'employe']);
        $employe->givePermissionTo([
            'view expenses',
            'create expenses',
            'view revenues',
        ]);

        $this->command->info('✅ Rôles et permissions créés avec succès !');
    }
}