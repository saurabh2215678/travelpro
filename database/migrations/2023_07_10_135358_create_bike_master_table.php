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
        Schema::create('bike_master', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->string('equivalent',255)->nullable();
            $table->integer('type')->nullable();
            $table->string('modal',100)->nullable();
            $table->string('image',255)->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('bike_master');
    }
};
