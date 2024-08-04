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
        Schema::create('package_departure_capacities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->nullable();
            $table->integer('package_departure_id')->nullable();
            $table->integer('package_price_id')->nullable();
            $table->integer('capacity')->nullable();
            $table->integer('booking')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_departure_capacities');
    }
};
