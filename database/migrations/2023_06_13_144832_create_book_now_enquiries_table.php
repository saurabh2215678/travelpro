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
        Schema::create('book_now_enquiries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable();
            $table->integer('package_id')->index('package_id');
            $table->mediumText('trip_date')->nullable();
            $table->string('service_level', 25)->nullable();
            $table->decimal('original_price', 10)->nullable();
            $table->decimal('final_pay_price', 10)->nullable();
            $table->string('name');
            $table->string('phone', 15);
            $table->string('email', 100);
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country');
            $table->string('zip_code', 25);
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
        Schema::dropIfExists('book_now_enquiries');
    }
};
