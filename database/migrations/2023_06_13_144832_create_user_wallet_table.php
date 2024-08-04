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
        Schema::create('user_wallet', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('txn_id', 20)->nullable();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 15);
            $table->decimal('fees', 15)->nullable();
            $table->string('payment_method', 20)->nullable();
            $table->decimal('balance', 15);
            $table->string('comment')->nullable();
            $table->date('for_date');
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
        Schema::dropIfExists('user_wallet');
    }
};
