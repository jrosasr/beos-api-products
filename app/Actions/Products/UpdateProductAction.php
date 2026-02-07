<?php

namespace App\Actions\Products;

use App\Models\Product;

class UpdateProductAction
{
    /**
     * Ejecuta la actualización de un producto existente.
     * 
     * @param Product $product
     * @param array $data Datos validados.
     * @return Product
     */
    public function execute(Product $product, array $data): Product
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($product, $data) {
            $product->update($data);
            return $product;
        });
    }
}
