<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir las monedas específicas usando el factory
        $currencies = [
            [
                'name' => 'USD', 
                'symbol' => 'US$', 
                'exchange_rate' => 0.002623983
            ],
            [
                'name' => 'VES', 
                'symbol' => 'Bs.', 
                'exchange_rate' => 381.10,
            ],
        ];

        foreach ($currencies as $data) {
            Currency::factory()->create($data);
        }
    }
}
