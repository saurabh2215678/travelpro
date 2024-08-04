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
        Schema::create('room_plan_includes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 155)->nullable();
            $table->boolean('available')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('is_featured')->nullable();
            $table->integer('sort_order')->nullable();
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
        Schema::dropIfExists('room_plan_includes');
    }
};
