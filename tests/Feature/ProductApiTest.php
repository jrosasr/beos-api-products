<?php

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed de roles y permisos
    $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
    
    // Crear moneda base
    $this->currency = Currency::factory()->create(['name' => 'USD', 'symbol' => '$']);
    
    // Crear usuario y asignar rol
    $this->user = User::factory()->create();
    $this->user->assignRole('user');
});

test('can list products', function () {
    Product::factory()->count(3)->create(['currency_id' => $this->currency->id]);

    $response = $this->actingAs($this->user)->getJson('/api/products');

    $response->assertStatus(200)
             ->assertJsonCount(3, 'data');
});

test('can create a product', function () {
    $data = [
        'name' => 'iPhone 15',
        'description' => 'Latest Apple phone',
        'price' => 999.99,
        'currency_id' => $this->currency->id,
        'tax_cost' => 150.00,
        'manufacturing_cost' => 450.00,
    ];

    $response = $this->actingAs($this->user)->postJson('/api/products', $data);

    $response->assertStatus(201)
             ->assertJsonPath('data.name', 'iPhone 15')
             ->assertJsonPath('data.price', 999.99);
    
    $this->assertDatabaseHas('products', [
        'name' => 'iPhone 15',
        'price' => 99999, // Guardado como entero x100
    ]);
});

test('can show a product', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);

    $response = $this->actingAs($this->user)->getJson("/api/products/{$product->id}");

    $response->assertStatus(200)
             ->assertJsonPath('data.id', $product->id);
});

test('can update a product', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);
    
    $data = [
        'name' => 'Updated Name',
        'price' => 799.50,
        'currency_id' => $this->currency->id,
        'tax_cost' => 100.00,
        'manufacturing_cost' => 300.00,
    ];

    $response = $this->actingAs($this->user)->putJson("/api/products/{$product->id}", $data);

    $response->assertStatus(200)
             ->assertJsonPath('data.name', 'Updated Name');
});

test('can delete a product', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);

    $response = $this->actingAs($this->user)->deleteJson("/api/products/{$product->id}");

    $response->assertStatus(200);
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('can list product prices', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);
    $currency2 = Currency::factory()->create(['name' => 'EUR']);
    ProductPrice::factory()->create([
        'product_id' => $product->id,
        'currency_id' => $currency2->id,
        'price' => 850.00
    ]);

    $response = $this->actingAs($this->user)->getJson("/api/products/{$product->id}/prices");

    $response->assertStatus(200)
             ->assertJsonCount(1, 'data');
});

test('can add a price in a different currency to a product', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);
    $currency2 = Currency::factory()->create(['name' => 'EUR']);
    
    $data = [
        'currency_id' => $currency2->id,
        'price' => 920.50
    ];

    $response = $this->actingAs($this->user)->postJson("/api/products/{$product->id}/prices", $data);

    $response->assertStatus(201)
             ->assertJsonPath('data.price', 920.50);
             
    $this->assertDatabaseHas('product_prices', [
        'product_id' => $product->id,
        'currency_id' => $currency2->id,
        'price' => 92050,
    ]);
});

/**
 * Tests de Robustez (Fallos)
 */

test('cannot create product with invalid data', function () {
    $data = [
        'name' => '', // Error: Requerido
        'price' => -10, // Error: Mínimo 0
        'currency_id' => 999, // Error: No existe
    ];

    $response = $this->actingAs($this->user)->postJson('/api/products', $data);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'price', 'currency_id']);
});

test('cannot access products without authentication', function () {
    $response = $this->getJson('/api/products');

    $response->assertStatus(401);
});

test('cannot create product without necessary permissions', function () {
    $unprivilegedUser = User::factory()->create();
    // No le asignamos rol 'user', por lo que no tiene permisos

    $response = $this->actingAs($unprivilegedUser)->postJson('/api/products', [
        'name' => 'Should fail',
        'price' => 100,
        'currency_id' => $this->currency->id,
        'tax_cost' => 10,
        'manufacturing_cost' => 50
    ]);

    $response->assertStatus(403);
});

test('returns 404 when product does not exist', function () {
    $response = $this->actingAs($this->user)->getJson('/api/products/9999');

    $response->assertStatus(404)
             ->assertJson([
                 'success' => false,
                 'message' => 'El recurso solicitado no existe.'
             ]);
});

test('cannot add price with invalid currency_id', function () {
    $product = Product::factory()->create(['currency_id' => $this->currency->id]);

    $response = $this->actingAs($this->user)->postJson("/api/products/{$product->id}/prices", [
        'currency_id' => 9999,
        'price' => 50
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['currency_id']);
});
