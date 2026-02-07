<?php

namespace App\Actions\Products;

use App\Models\Product;

class DeleteProductAction
{
    /**
     * Ejecuta la eliminación de un producto.
     * 
     * @param Product $product
     * @return void
     */
    public function execute(Product $product): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($product) {
            $product->delete();
        });
    }
}
