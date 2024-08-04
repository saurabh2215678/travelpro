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
        Schema::create('search_query', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('fromCityOrAirport', 10);
            $table->string('toCityOrAirport', 10);
            $table->date('travelDate');
            $table->integer('cabinClass');
            $table->integer('adult');
            $table->integer('child');
            $table->integer('infant');
            $table->tinyInteger('isDirectFlight');
            $table->tinyInteger('isConnectingFlight');
            $table->mediumText('json_Response')->nullable();
            $table->dateTime('valid_till');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('search_query');
    }
};
