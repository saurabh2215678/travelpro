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
        Schema::create('home_cms', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 55);
            $table->string('module', 55);
            $table->string('show_title', 55);
            $table->text('params')->nullable();
            $table->integer('sort_order');
            $table->string('orderby_name', 25)->nullable();
            $table->string('orderby', 25)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_cms');
    }
};
