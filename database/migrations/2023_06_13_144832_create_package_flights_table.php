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
        Schema::create('package_flights', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->nullable();
            $table->string('flight_number', 100)->nullable();
            $table->string('flight_time', 100)->nullable();
            $table->string('airline_name', 100)->nullable();
            $table->string('flight_departure', 100)->nullable();
            $table->string('flight_arrival', 100)->nullable();
            $table->mediumText('flight_comment')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('package_flights');
    }
};
