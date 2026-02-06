<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'tax_cost' => ['required', 'numeric', 'min:0'],
            'manufacturing_cost' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * Preparar los datos antes de la validación.
     */
    protected function prepareForValidation(): void
    {
        // Ejemplo: Asegurar que los costos sean numéricos si vienen como strings con comas
        if ($this->has('price')) {
            $this->merge([
                'price' => str_replace(',', '.', $this->price),
            ]);
        }
    }
}
