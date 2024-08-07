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
        Schema::create('airline_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('name',100);
            $table->string('source',100);
            $table->string('source_terminal',100);
            $table->string('destination',100);
            $table->string('destination_terminal',100);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('is_arrival_next_day')->default(0);
            $table->string('airline',100);
            $table->string('flight_number',100);
            $table->integer('stops')->default(0);
            $table->string('trip_type',100)->comment('domestic,international');
            $table->string('journey_type',100)->comment('oneway, roundtrip');
            $table->integer('disable_before_departure_hour')->default(0);
            $table->integer('featured')->default(0);
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
        Schema::dropIfExists('airline_routes');
    }
};
