<?php

namespace App\Actions\Products;

use App\Models\Product;

class DeleteProductAction
{
    /**
     * Ejecuta la eliminación de un producto.
     * 
     * @param Product $product
     * @return bool|null
     */
    public function execute(Product $product): ?bool
    {
        return $product->delete();
    }
}
