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
        Schema::create('accommodation_plans', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accommodation_id');
            $table->integer('room_id');
            $table->string('plan_name', 55);
            $table->string('meal_type', 20);
            $table->text('plan_json_data')->nullable();
            $table->boolean('status')->nullable();
            $table->dateTime('created_at');
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
        Schema::dropIfExists('accommodation_plans');
    }
};
