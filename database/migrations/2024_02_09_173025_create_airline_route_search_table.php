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
        Schema::create('airline_route_search', function (Blueprint $table) {
            $table->id();
            $table->string('slug',100);
            $table->string('source',3);
            $table->string('destination',3);
            $table->date('dep')->nullable();
            $table->integer('tripType')->default(0);
            $table->string('pClass',20);
            $table->integer('ADT')->default(0);
            $table->integer('CHD')->default(0);
            $table->integer('INF')->default(0);
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
        Schema::dropIfExists('airline_route_search');
    }
};
