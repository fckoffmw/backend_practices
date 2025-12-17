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
        Schema::create('sales_statistics', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('revenue', 12, 2);
            $table->date('sale_date');
            $table->string('region');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->timestamps();

            // Индексы для оптимизации запросов
            $table->index(['category']);
            $table->index(['region']);
            $table->index(['sale_date']);
            $table->index(['customer_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_statistics');
    }
};