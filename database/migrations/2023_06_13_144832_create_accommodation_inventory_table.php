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
        Schema::create('accommodation_inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accommodation_id');
            $table->integer('room_id');
            $table->date('date');
            $table->integer('plan_id')->nullable();
            $table->string('inventory', 10);
            $table->integer('booked')->default(0);
            $table->decimal('price', 15);
            $table->decimal('single_price', 15)->nullable();
            $table->decimal('extra_adult', 15)->nullable();
            $table->decimal('extra_child_1', 15)->nullable();
            $table->decimal('extra_child_2', 15)->nullable();
            $table->tinyInteger('status');
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
        Schema::dropIfExists('accommodation_inventory');
    }
};
