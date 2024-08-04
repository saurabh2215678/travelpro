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
        Schema::create('cabs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('equivalent')->nullable();
            $table->integer('seats')->default(0);
            $table->decimal('base_price',15,2)->default(0);
            $table->decimal('chauffeur_charge',15,2)->default(0);
            $table->decimal('night_stay_allowance',15,2)->default(0);
            $table->string('image')->nullable();
            $table->integer('status')->default(0);
            $table->integer('sort_order')->default(0);
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
        Schema::dropIfExists('cabs');
    }
};
