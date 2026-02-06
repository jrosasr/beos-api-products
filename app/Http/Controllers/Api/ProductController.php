<?php

namespace App\Http\Controllers\Api;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\CreateProductPriceAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductPriceRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Resources\ProductPriceResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Listado de productos
     * 
     * Obtiene una lista de todos los productos registrados con su divisa base.
     */
    public function index(): JsonResponse
    {
        $products = Product::with('currency')->get();

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products)
        ]);
    }

    /**
     * Crear producto
     * 
     * Registra un nuevo producto en el sistema.
     */
    public function store(StoreProductRequest $request, CreateProductAction $action): JsonResponse
    {
        $product = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Producto creado con éxito',
            'data' => new ProductResource($product->load('currency'))
        ], 201);
    }

    /**
     * Detalle de producto
     * 
     * Obtiene la información detallada de un producto específico, incluyendo sus precios en otras divisas.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product->load(['currency', 'prices.currency']))
        ]);
    }

    /**
     * Actualizar producto
     * 
     * Modifica los datos de un producto existente.
     */
    public function update(StoreProductRequest $request, Product $product, UpdateProductAction $action): JsonResponse
    {
        $product = $action->execute($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado con éxito',
            'data' => new ProductResource($product->load('currency'))
        ]);
    }

    /**
     * Eliminar producto
     * 
     * Elimina permanentemente un producto del catálogo.
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
     * Listar precios por divisa
     * 
     * Obtiene todos los precios registrados para un producto en diferentes divisas.
     */
    public function prices(Product $product): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ProductPriceResource::collection($product->prices()->with('currency')->get())
        ]);
    }

    /**
     * Añadir precio en divisa
     * 
     * Registra un nuevo precio para el producto en una divisa específica.
     */
    public function storePrice(StoreProductPriceRequest $request, Product $product, CreateProductPriceAction $action): JsonResponse
    {
        $price = $action->execute($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Precio registrado con éxito',
            'data' => new ProductPriceResource($price->load('currency'))
        ], 201);
    }
}
