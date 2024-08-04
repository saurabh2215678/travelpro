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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            // $table->integer('agent_id')->nullable();
            $table->integer('package_id')->nullable()->index('order_package_id');
            $table->string('package_name')->nullable();
            $table->integer('package_type')->nullable();
            $table->string('package_type_name')->nullable();
            $table->string('order_no', 20)->nullable();
            $table->tinyInteger('order_type')->nullable()->default(1)->comment('1=package,2=pay-online');
            $table->integer('last_payment_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 15);
            $table->integer('country_code')->nullable();
            $table->string('country', 100);
            $table->string('address1', 100)->nullable();
            $table->string('address2', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip_code', 100)->nullable();
            $table->string('method', 20)->nullable()->comment('1=>Paypal,2=>Bank Transfer');
            $table->mediumText('comment')->nullable();
            $table->integer('inventory')->nullable();
            $table->boolean('payment_status');
            $table->mediumText('payment_description')->nullable();
            $table->mediumText('payment_response')->nullable();
            $table->decimal('discount', 10)->nullable()->default(0);
            $table->text('discount_details');
            $table->string('discount_type', 10)->nullable();
            $table->decimal('sub_total_amount', 10)->nullable()->default(0);
            $table->decimal('fees', 10)->nullable()->default(0);
            $table->decimal('total_amount', 10)->nullable()->default(0);
            $table->decimal('partial_amount', 15)->nullable();
            $table->string('pay_type', 100)->nullable();
            $table->decimal('supplier_markup', 15);
            $table->decimal('admin_markup', 15);
            $table->text('admin_markup_details');
            $table->decimal('markup', 15);
            $table->text('markup_details');
            $table->integer('hide_markup');
            $table->decimal('commission', 15);
            $table->text('commission_details');
            $table->dateTime('trip_date')->nullable();
            $table->mediumText('price_category_choice_record')->nullable();
            $table->longText('flight_details')->nullable();
            $table->string('bookingId', 100)->nullable();
            $table->longText('booking_details')->nullable();
            $table->decimal('supplier_total_amount', 15);
            $table->integer('orders_status')->nullable();
            $table->integer('hide_agent_details');
            $table->integer('hide_price_details');
            $table->string('status', 100);
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
        Schema::dropIfExists('orders');
    }
};
