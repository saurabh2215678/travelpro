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
        Schema::create('orders_traveller', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 15);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('pax_type', 20);
            $table->date('dob')->nullable();
            $table->string('passport_nationality', 10)->nullable();
            $table->string('passport_no', 50)->nullable();
            $table->date('passport_exp_date')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->integer('order_id');
            $table->integer('serial_no');
            $table->mediumText('ssrBaggageInfos')->nullable();
            $table->mediumText('ssrMealInfos')->nullable();
            $table->mediumText('ssrSeatInfos')->nullable();
            $table->integer('save_passenger_details')->nullable()->default(0);
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
        Schema::dropIfExists('orders_traveller');
    }
};
