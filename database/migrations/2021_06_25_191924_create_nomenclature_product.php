<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNomenclatureProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomenclature_product', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->references('id')->on('products');
            $table->foreignUuid('nomenclature_id')->references('id')->on('nomenclatures');
            $table->string('nomenclature_value')->nullable();
            $table->boolean('has_time')->default(0);
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('compare_at_price', 8, 2)->nullable();
            $table->decimal('inventory_cost', 8, 2)->nullable();
            $table->string('sku', 15)->unique()->nullable();
            $table->string('barcode', 25)->unique()->nullable();
            $table->integer('stock')->nullable();
            $table->string('thumbnail_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nomenclature_product');
    }
}
