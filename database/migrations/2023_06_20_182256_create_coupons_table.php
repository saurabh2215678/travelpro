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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('name',255)->nullable();
            $table->string('code',100)->nullable();
            $table->string('type',10)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('use_limit')->nullable();
            $table->decimal('min_amount', 15, 2)->nullable();
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->decimal('max_discount_amt', 10, 2)->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
