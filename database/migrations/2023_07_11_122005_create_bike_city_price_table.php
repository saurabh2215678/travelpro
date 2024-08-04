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
        Schema::create('bike_city_price', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id')->nullable();
            $table->integer('bike_id')->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->decimal('round_trip_price',15,2)->nullable();
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
        Schema::dropIfExists('bike_city_price');
    }
};
