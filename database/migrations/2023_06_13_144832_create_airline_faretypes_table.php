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
        Schema::create('airline_faretypes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('fare_types', 100);
            $table->string('description', 100);
            $table->string('color', 10);
            $table->string('ui', 100);
            $table->string('api', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airline_faretypes');
    }
};
