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
        Schema::create('cabs_intentory', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id')->index('city_id');
            $table->integer('cab_id')->index('cab_id');
            $table->date('trip_date');
            $table->integer('inventory')->default(0);
            $table->integer('booked')->default(0);
            $table->integer('status');
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
        Schema::dropIfExists('cabs_intentory');
    }
};
