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
        Schema::create('bike_city_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id')->nullable();
            $table->integer('bike_id')->nullable();
            $table->date('trip_date')->nullable();
            $table->integer('inventory')->nullable();
            $table->integer('booked')->default(0);
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('bike_city_inventory');
    }
};
