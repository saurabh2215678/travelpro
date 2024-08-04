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
        Schema::create('cab_master', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->string('equivalent')->nullable();
            $table->integer('seats')->nullable();
            $table->string('image')->nullable();
            $table->integer('status')->nullable();
            $table->integer('sort_order');
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
        Schema::dropIfExists('cab_master');
    }
};
