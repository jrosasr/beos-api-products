<?php

namespace App\Http\Controllers\Api;

use App\Actions\Currencies\ListCurrenciesAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    /**
     * Listado de divisas
     * 
     * Obtiene una lista de todas las divisas disponibles en el sistema.
     */
    public function index(ListCurrenciesAction $action): JsonResponse
    {
        $currencies = $action->execute();

        return response()->json([
            'success' => true,
            'data' => CurrencyResource::collection($currencies)
        ]);
    }
}
