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
        Schema::create('orders_status_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('order_id')->default(0);
            $table->string('orders_status', 20)->nullable();
            $table->mediumText('comments')->nullable();
            $table->enum('created_type', ['backend', 'customer', 'agent', ''])->nullable()->default('customer');
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('orders_status_history');
    }
};
