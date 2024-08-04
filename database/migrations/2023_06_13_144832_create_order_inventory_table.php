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
        Schema::create('order_inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('module_name', 55);
            $table->integer('inventory_id');
            $table->integer('user_id');
            $table->string('order_id', 22);
            $table->integer('booking_inventory');
            $table->dateTime('created_at')->useCurrent();
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
        Schema::dropIfExists('order_inventory');
    }
};
