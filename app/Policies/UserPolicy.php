<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determina si el usuario puede ver el perfil.
     */
    public function viewProfile(User $user, $model = null): bool
    {
        // Si el modelo es el nombre de la clase (check nivel clase) o null
        if (!$model instanceof User) {
            return $user->can('auth:profile');
        }

        return $user->id === $model->id || $user->can('auth:profile');
    }

    /**
     * Determina si el usuario puede cerrar la sesión.
     */
    public function logout(User $user, $model = null): bool
    {
        // Si el modelo es el nombre de la clase (check nivel clase) o null
        if (!$model instanceof User) {
            return $user->can('auth:logout');
        }

        return $user->id === $model->id || $user->can('auth:logout');
    }
}
