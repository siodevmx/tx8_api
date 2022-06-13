<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('nomenclature_product_id')->references('id')->on('nomenclature_product');
            $table->time('start_at')->nullable();
            $table->time('finish_at')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('compare_at_price', 8, 2)->nullable();
            $table->foreignUuid('time_zone_id')->nullable()->references('id')->on('time_zones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hours_prices');
    }
}
