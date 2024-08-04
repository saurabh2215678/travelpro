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
        Schema::create('payments_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('payment_id');
            $table->decimal('amount', 15)->default(0);
            $table->string('currency', 50);
            $table->integer('user_id')->index('user_id');
            $table->string('name', 120);
            $table->string('email', 120);
            $table->string('address');
            $table->string('phone', 60);
            $table->integer('phone_country')->nullable();
            $table->string('payment_channel', 60)->comment('RazorPay or any other');
            $table->string('description');
            $table->integer('order_id')->index('order_id');
            $table->string('status', 60);
            $table->string('payment_type', 100)->comment('cash, cheque, online');
            $table->mediumText('payment_detail')->comment('cheque- bank, cheqno');
            $table->decimal('refunded_amount', 15)->default(0);
            $table->string('refund_note');
            $table->string('customer_type', 100);
            $table->string('pg_order_id', 50)->nullable()->comment('order_id from payment gateway');
            $table->string('pg_payment_id', 50)->comment('payment_id from payment gateway');
            $table->longText('pg_response')->comment('response from payment gateway');
            $table->string('pg_payment_status', 30)->comment('payment status from payment gateway');
            $table->integer('pg_created_at')->nullable();
            $table->dateTime('pg_response_date')->comment('datetime of response from payment gateway');
            $table->dateTime('created_at')->useCurrent();
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
        Schema::dropIfExists('payments_history');
    }
};
