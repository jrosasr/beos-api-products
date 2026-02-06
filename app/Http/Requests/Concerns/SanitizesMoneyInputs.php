<?php

namespace App\Http\Requests\Concerns;

trait SanitizesMoneyInputs
{
    /**
     * Sanea los campos monetarios reemplazando comas por puntos.
     *
     * @param array $fields
     * @return void
     */
    protected function sanitizeMoney(array $fields): void
    {
        foreach ($fields as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $this->merge([
                    $field => str_replace(',', '.', $this->input($field)),
                ]);
            }
        }
    }
}
