<?php

namespace App\Actions\Products;

use App\Models\Product;
use App\Models\ProductPrice;

class CreateProductPriceAction
{
    /**
     * Ejecuta el registro de un nuevo precio para un producto.
     * 
     * @param Product $product
     * @param array $data Datos validados (currency_id, price).
     * @return ProductPrice
     */
    public function execute(Product $product, array $data): ProductPrice
    {
        return $product->prices()->create($data);
    }
}
