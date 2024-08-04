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
        Schema::create('airline_route_inventory', function (Blueprint $table) {
            $table->id();
            $table->integer('airline_route_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('fare_type')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('inventory')->default(0);
            $table->string('pnr',100);
            $table->decimal('adult_price',15, 2);
            $table->decimal('child_price',15, 2);
            $table->decimal('infant_price',15, 2);
            $table->decimal('markup',15, 2);
            $table->integer('markup_type')->default(0);
            $table->integer('is_refundable')->default(0);
            $table->string('flight_class',100);
            $table->integer('sort_order')->default(0);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('airline_route_inventory');
    }
};
