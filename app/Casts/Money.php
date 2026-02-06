<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class Money implements CastsAttributes
{
    /**
     * Transformar el valor de la base de datos (entero/céntimos) a decimal.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return round($value / 100, 2);
    }

    /**
     * Transformar el valor decimal a entero para guardar en la base de datos.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        return (int) round($value * 100);
    }
}
