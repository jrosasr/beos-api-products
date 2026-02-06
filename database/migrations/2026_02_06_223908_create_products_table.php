<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nombre del producto');
            $table->text('description')->nullable()->comment('Descripción del producto');
            $table->bigInteger('price')->comment('Precio del producto en céntimos');
            $table->foreignId('currency_id')->constrained('currencies')->comment('Moneda del producto');
            $table->bigInteger('tax_cost')->comment('Costo de impuestos del producto en céntimos');
            $table->bigInteger('manufacturing_cost')->comment('Costo de fabricación del producto en céntimos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
