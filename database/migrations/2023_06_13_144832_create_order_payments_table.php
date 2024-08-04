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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->decimal('amount', 15)->default(0);
            $table->string('currency', 50);
            $table->integer('user_id')->index('user_id');
            $table->string('payment_channel', 60)->comment('RazorPay or any other');
            $table->string('description');
            $table->string('order_id', 20)->nullable()->index('order_id');
            $table->string('order_no', 20)->nullable();
            $table->string('status', 60);
            $table->string('payment_type', 100);
            $table->string('payment_method', 20)->nullable();
            $table->mediumText('payment_detail')->comment('cheque- bank, cheqno');
            $table->string('ch_account_holder', 120)->nullable();
            $table->string('ch_account_no', 20)->nullable();
            $table->string('ch_bank_name', 120)->nullable();
            $table->string('ch_branch_name', 120)->nullable();
            $table->decimal('ch_amount', 15)->nullable();
            $table->string('ch_account_type', 55)->nullable();
            $table->string('ch_no', 120)->nullable();
            $table->date('ch_date')->nullable();
            $table->mediumText('ch_file')->nullable();
            $table->decimal('refunded_amount', 15)->default(0);
            $table->string('refund_note');
            $table->string('customer_type', 100);
            $table->string('pg_order_id', 50)->nullable()->comment('order_id from payment gateway');
            $table->string('pg_payment_id', 50)->comment('payment_id from payment gateway');
            $table->longText('pg_response')->comment('response from payment gateway');
            $table->text('pg_description')->nullable();
            $table->boolean('pg_payment_status')->comment('payment status from payment gateway');
            $table->string('settlement_status', 60)->nullable();
            $table->string('settlement_utr')->nullable();
            $table->dateTime('settlement_date')->nullable();
            $table->string('transfer_status', 60)->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->integer('pg_created_at')->nullable();
            $table->dateTime('pg_response_date')->nullable()->comment('datetime of response from payment gateway');
            $table->string('transfer_bank_account', 20);
            $table->string('transfer_bank_name', 30);
            $table->string('transfer_sub_account', 20);
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
        Schema::dropIfExists('order_payments');
    }
};
