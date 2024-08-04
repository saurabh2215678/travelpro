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
        Schema::table('order_payments', function (Blueprint $table) {
            if (Schema::hasColumn('order_payments', 'payment_method')) {
                $table->string('payment_method',255)->nullable()->change();
            }
        });

        Schema::table('user_wallet', function (Blueprint $table) {
            if (Schema::hasColumn('user_wallet', 'payment_method')) {
                $table->string('payment_method',255)->nullable()->change();
            }
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            //
        });
    }
};
