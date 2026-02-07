<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\{getJson, artisan};

uses(RefreshDatabase::class);

beforeEach(function () {
    artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    artisan('db:seed', ['--class' => 'CurrencySeeder']);
});

test('can list currencies', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $response = $this->actingAs($user)->getJson('/api/currencies');

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'data' => [
                     '*' => ['id', 'name', 'symbol']
                 ]
             ]);
});

test('cannot list currencies without authentication', function () {
    $response = getJson('/api/currencies');

    $response->assertStatus(401);
});
