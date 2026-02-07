<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    /**
     * Create a new user, assign default role and generate token.
     *
     * @param array $data
     * @return array Contains user and token
     */
    public function execute(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign default role
            $user->assignRole('user');

            // Generate token
            $token = $user->createToken('API Access')->plainTextToken;

            return compact('user', 'token');
        });
    }
}
