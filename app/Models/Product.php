<?php

namespace App\Models;

use App\Casts\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency_id',
        'tax_cost',
        'manufacturing_cost',
    ];

    protected $casts = [
        'price' => Money::class,
        'tax_cost' => Money::class,
        'manufacturing_cost' => Money::class,
    ];

    /**
     * Obtener la divisa base del producto.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Obtener los precios registrados en otras divisas.
     */
    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }
}
