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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear rol 'user' y asignar permisos
        $role = Role::firstOrCreate(['name' => 'user']);
        $role->syncPermissions($permissions);
    }
}
