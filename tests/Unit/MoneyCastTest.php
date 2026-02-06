<?php

use App\Casts\Money;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model {
    protected $casts = ['price' => Money::class];
}

test('money cast converts decimal to integer for storage', function () {
    $cast = new Money();
    $model = new TestModel();
    
    $result = $cast->set($model, 'price', 19.99, []);
    
    expect($result)->toBe(1999);
});

test('money cast converts integer to decimal for retrieval', function () {
    $cast = new Money();
    $model = new TestModel();
    
    $result = $cast->get($model, 'price', 1999, []);
    
    expect($result)->toBe(19.99);
});

test('money cast handles truncation correctly', function () {
    $cast = new Money();
    $model = new TestModel();
    
    // 19.995 should truncate to 19.99 (1999 cents)
    $result = $cast->set($model, 'price', 19.995, []);
    expect($result)->toBe(1999);
    
    // 19.999 should also truncate to 19.99 (1999 cents)
    $result = $cast->set($model, 'price', 19.999, []);
    expect($result)->toBe(1999);
    
    // 20.00 should remain 20.00 (2000 cents)
    $result = $cast->set($model, 'price', 20.00, []);
    expect($result)->toBe(2000);
});
