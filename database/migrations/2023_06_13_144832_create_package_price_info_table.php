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
        Schema::create('package_price_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('package_id')->index('package_id');
            $table->string('title');
            $table->boolean('service_level')->default(true);
            $table->boolean('discount_type')->nullable()->comment('(1 => Flat, 2 => Percent)');
            $table->decimal('discount', 10)->nullable();
            $table->mediumText('description')->nullable();
            $table->decimal('booking_price', 15);
            $table->decimal('sub_total_amount', 15)->nullable();
            $table->decimal('final_amount', 15)->nullable();
            $table->mediumText('category_choices_record')->nullable();
            $table->mediumText('show_choices_record')->nullable();
            $table->boolean('is_partial_amount');
            $table->boolean('is_book_without_payment');
            $table->enum('price_validity', ['default', 'specific'])->default('default');
            $table->boolean('status');
            $table->boolean('sort_order');
            $table->boolean('is_default');
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
        Schema::dropIfExists('package_price_info');
    }
};
