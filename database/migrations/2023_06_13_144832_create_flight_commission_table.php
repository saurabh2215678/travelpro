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
        Schema::create('flight_commission', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('code', 20);
            $table->string('name', 20);
            $table->string('offer_markup_type', 10);
            $table->integer('offer_markup_value');
            $table->string('online_markup_type', 10);
            $table->integer('online_markup_value');
            $table->string('offer_commission_type', 10);
            $table->integer('offer_commission_value');
            $table->string('online_commission_type', 10);
            $table->integer('online_commission_value');
            $table->string('offer_discount_type', 10);
            $table->integer('offer_discount_value');
            $table->string('online_discount_type', 10);
            $table->integer('online_discount_value');
            $table->integer('sort_order');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('flight_commission');
    }
};
