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
        Schema::create('order_amendments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id');
            $table->string('bookingId', 100);
            $table->string('amendmentId', 100);
            $table->string('amendment_type', 100);
            $table->text('request_json');
            $table->text('response_json');
            $table->text('amendment_details');
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
        Schema::dropIfExists('order_amendments');
    }
};
