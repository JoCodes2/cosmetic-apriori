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
        Schema::create('billings_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_product')->constrained('tb_product')->cascadeOnDelete();
            $table->foreignUuid('id_billing')->constrained('billings')->cascadeOnDelete();
            $table->string('name_product');
            $table->integer('price_product');
            $table->integer('qty');
            $table->integer('total_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings_items');
    }
};
