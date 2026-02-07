<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar permisos en cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'products.index',
            'products.store',
            'products.show',
            'products.update',
            'products.destroy',
            'products.prices',
            'products.storePrice',
            'auth:profile',
            'auth:logout',
            'currencies.index',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission); // Usa el guard por defecto (web)
        }

        // Crear rol 'user' y asignar permisos
        $role = Role::findOrCreate('user');
        $role->syncPermissions($permissions);
    }
}
