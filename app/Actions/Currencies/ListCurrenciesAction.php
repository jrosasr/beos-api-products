<?php

namespace App\Actions\Currencies;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class ListCurrenciesAction
{
    /**
     * Get all available currencies.
     *
     * @return Collection
     */
    public function execute(): Collection
    {
        return Currency::all();
    }
}
