<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{postJson, artisan};

uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
});

test('user can register', function () {
    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = postJson('/api/register', $data);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'success',
                 'message',
                 'data' => [
                     'user' => ['id', 'name', 'email', 'roles', 'permissions'],
                     'token'
                 ]
             ]);

    $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    
    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->hasRole('user'))->toBeTrue();
});

test('user can login', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password123')
    ]);
    $user->assignRole('user');

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password123'
    ]);

    $response->assertStatus(200)
             ->assertJsonPath('success', true)
             ->assertJsonStructure(['data' => ['token']]);
});

test('user can get their profile', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                     ->getJson('/api/profile');

    $response->assertStatus(200)
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.email', $user->email);
});

test('user can logout', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                     ->postJson('/api/logout');

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Sesión cerrada correctamente');
             
    $this->assertCount(0, $user->tokens);
});

test('user cannot register with existing email', function () {
    User::factory()->create(['email' => 'duplicate@example.com']);

    $data = [
        'name' => 'Other User',
        'email' => 'duplicate@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = postJson('/api/register', $data);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

test('user cannot register with weak password', function () {
    $data = [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => '123', // Demasiado corta
        'password_confirmation' => '123',
    ];

    $response = postJson('/api/register', $data);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

test('user cannot login with wrong credentials', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct_password')
    ]);

    $response = postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong_password'
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

test('user cannot logout if not authenticated', function () {
    $response = postJson('/api/logout');

    $response->assertStatus(401);
});
