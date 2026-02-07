<?php

namespace App\Actions\Products;

use App\Models\Product;

class CreateProductAction
{
    /**
     * Ejecuta la creación de un nuevo producto.
     * 
     * @param array $data Datos validados.
     * @return Product
     */
    public function execute(array $data): Product
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            // El cast Money se encarga de convertir el decimal a entero (x100) al guardar
            return Product::create($data);
        });
    }
}
