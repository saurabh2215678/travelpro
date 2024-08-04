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
        Schema::create('webhook_responses', function (Blueprint $table) {
            $table->id();
            $table->string('method',10)->nullable();
            $table->string('from',50)->nullable();
            $table->string('event_type',50)->nullable();
            $table->string('razorpay_payment_id',60)->nullable();
            $table->text('headers')->nullable();
            $table->longtext('request_body')->nullable();
            $table->longtext('request_data')->nullable();
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
        Schema::dropIfExists('webhook_responses');
    }
};
