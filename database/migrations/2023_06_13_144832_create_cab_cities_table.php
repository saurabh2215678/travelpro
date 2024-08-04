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
        Schema::create('cab_cities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
            $table->boolean('status')->nullable()->default(true);
            $table->integer('sort_order')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('is_airport')->nullable();
            $table->boolean('is_deleted')->nullable();
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
        Schema::dropIfExists('cab_cities');
    }
};
