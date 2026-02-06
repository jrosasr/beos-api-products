<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('products.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->can('products.show');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('products.store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->can('products.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->can('products.destroy');
    }

    /**
     * Determine whether the user can view product prices.
     */
    public function viewPrices(User $user, Product $product): bool
    {
        return $user->can('products.prices');
    }

    /**
     * Determine whether the user can add a price to the product.
     */
    public function addPrice(User $user, Product $product): bool
    {
        return $user->can('products.storePrice');
    }
}
