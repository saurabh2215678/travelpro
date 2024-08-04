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
        Schema::create('package_airlines', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('airline_name');
            $table->string('airline_code', 10)->nullable();
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->boolean('sort_order')->nullable();
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
        Schema::dropIfExists('package_airlines');
    }
};
