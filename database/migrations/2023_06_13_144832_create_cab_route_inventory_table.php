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
        Schema::create('cab_route_inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cab_route_group_id')->index('cab_route_group_id');
            $table->integer('cab_id')->index('cab_id');
            $table->date('trip_date');
            $table->integer('inventory');
            $table->integer('booked')->default(0);
            $table->integer('status');
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
        Schema::dropIfExists('cab_route_inventory');
    }
};
