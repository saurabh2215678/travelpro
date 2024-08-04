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
        Schema::create('orders_old', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id');
            $table->string('order_no')->nullable();
            $table->string('name');
            $table->string('email', 25);
            $table->string('phone', 15);
            $table->string('country', 100);
            $table->string('method', 20);
            $table->text('comment')->nullable();
            $table->boolean('payment_status');
            $table->mediumText('payment_description')->nullable();
            $table->mediumText('payment_response')->nullable();
            $table->decimal('discount', 10);
            $table->string('discount_type', 10);
            $table->decimal('sub_total_amount', 10);
            $table->decimal('total_amount', 10);
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
        Schema::dropIfExists('orders_old');
    }
};
