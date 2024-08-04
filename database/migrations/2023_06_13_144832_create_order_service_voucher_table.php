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
        Schema::create('order_service_voucher', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id');
            $table->integer('package_id');
            $table->string('name_of_pax', 55);
            $table->string('pax_contact', 20);
            $table->string('local_contact', 20);
            $table->string('agent_contact', 55);
            $table->string('title', 55)->nullable();
            $table->string('trip_type', 25)->nullable();
            $table->string('gst_no', 30)->nullable();
            $table->string('name', 55)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 55)->nullable();
            $table->text('package_data')->nullable();
            $table->text('cab_data')->nullable();
            $table->mediumText('hotel_data');
            $table->string('vehicle_type', 155);
            $table->mediumText('flight_data');
            $table->string('payment_mode', 55);
            $table->string('remarks');
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
        Schema::dropIfExists('order_service_voucher');
    }
};
