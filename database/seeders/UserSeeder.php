<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usar factory para crear el usuario administrador
        $user = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('user');

        // Opcional: Crear algunos usuarios aleatorios adicionales
        User::factory()->count(3)->create()->each(function ($u) {
            $u->assignRole('user');
        });
    }
}
