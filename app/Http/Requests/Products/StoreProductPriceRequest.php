<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Concerns\SanitizesMoneyInputs;

class StoreProductPriceRequest extends FormRequest
{
    use SanitizesMoneyInputs;
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true; // Controlado por Policies
    }

    /**
     * Obtener las reglas de validación que se aplican a la solicitud.
     */
    public function rules(): array
    {
        return [
            'currency_id' => ['required', 'exists:currencies,id'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Preparar los datos antes de la validación.
     */
    protected function prepareForValidation(): void
    {
        $this->sanitizeMoney(['price']);
    }
}
