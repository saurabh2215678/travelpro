<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airline_price_markup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('markup_type', 10);
            $table->integer('markup_value');
            $table->integer('markup_value_nonc');
            $table->integer('max_base_price');
            $table->integer('is_domestic');
            $table->integer('featured');
            $table->integer('sort_order');
            $table->integer('status')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airline_price_markup');
    }
};
