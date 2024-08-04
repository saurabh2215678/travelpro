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
        Schema::create('tbl_merchant', function (Blueprint $table) {
            $table->bigIncrements('merchant_id')->unique('merchant_id');
            $table->string('payment_type');
            $table->string('account_id');
            $table->string('merchant_key');
            $table->mediumText('return_path');
            $table->string('api_key');
            $table->mediumText('description');
            $table->integer('status');
            $table->date('date_added');
            $table->date('date_modified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_merchant');
    }
};
