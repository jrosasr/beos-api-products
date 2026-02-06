<?php

namespace App\Http\Controllers\Api;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\CreateProductPriceAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductPriceRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Obtener lista de productos.
     */
    public function index(): JsonResponse
    {
        $products = Product::with('currency')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Producto creado con éxito',
            'data' => $product->load('currency')
        ], 201);
    }

    /**
     * Obtener un producto por ID.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $product->load(['currency', 'prices.currency'])
        ]);
    }

    /**
     * Actualizar un producto.
     */
    public function update(StoreProductRequest $request, Product $product, UpdateProductAction $action): JsonResponse
    {
        $product = $action->execute($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado con éxito',
            'data' => $product->load('currency')
        ]);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product, DeleteProductAction $action): JsonResponse
    {
        $action->execute($product);

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ]);
    }

    /**
     * Obtener lista de precios de un producto.
     */
    public function prices(Product $product): JsonResponse
    {
        $this->authorize('viewPrices', $product);

        return response()->json([
            'success' => true,
            'data' => $product->prices()->with('currency')->get()
        ]);
    }

    /**
     * Crear un nuevo precio para un producto.
     */
    public function storePrice(StoreProductPriceRequest $request, Product $product, CreateProductPriceAction $action): JsonResponse
    {
        $this->authorize('addPrice', $product);

        $price = $action->execute($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Precio registrado con éxito',
            'data' => $price->load('currency')
        ], 201);
    }
}
