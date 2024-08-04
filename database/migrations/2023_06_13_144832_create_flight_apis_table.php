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
        Schema::create('flight_apis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 25)->nullable();
            $table->string('value', 25);
            $table->unsignedBigInteger('payment_method_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('is_online')->default(1);
            $table->boolean('status')->default(false);
            $table->tinyInteger('show')->nullable();
            $table->integer('sort_order')->nullable();
            $table->string('details')->nullable();
            $table->text('perameter_1')->nullable();
            $table->string('perameter_2', 191)->nullable();
            $table->string('perameter_3', 191)->nullable();
            $table->string('perameter_4', 191)->nullable();
            $table->string('perameter_5', 191)->nullable();
            $table->string('perameter_6', 191)->nullable();
            $table->string('perameter_7', 191)->nullable();
            $table->text('image')->nullable();
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
        Schema::dropIfExists('flight_apis');
    }
};
