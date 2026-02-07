<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recuperamos las monedas existentes (creadas en CurrencySeeder)
        $currencies = Currency::all();

        // Si no hay monedas, creamos algunas para evitar fallos
        if ($currencies->isEmpty()) {
            $currencies = Currency::factory()->count(3)->create();
        }

        // Usamos recycle() para que los factories de Product y ProductPrice
        // elijan de las monedas existentes en lugar de intentar crear nuevas
        // que podrían duplicar el código (unique constraint).
        Product::factory()
            ->count(4)
            ->recycle($currencies)
            ->hasPrices(1)
            ->create();
    }
}
