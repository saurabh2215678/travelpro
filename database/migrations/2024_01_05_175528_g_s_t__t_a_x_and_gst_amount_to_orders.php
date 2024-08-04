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
        Schema::table('orders', function (Blueprint $table) {
            if(!Schema::hasColumn('orders', 'GST_TAX')) {
                $table->decimal('GST_TAX',10,2)->after('payment_response')->default('0.00');
            }
            if(!Schema::hasColumn('orders', 'gst_amount')) {
                $table->decimal('gst_amount',10,2)->after('GST_TAX')->default('0.00');
            }
            if(!Schema::hasColumn('orders', 'TCS_TAX')) {
                $table->decimal('TCS_TAX',10,2)->after('gst_amount')->default('0.00');
            }
            if(!Schema::hasColumn('orders', 'tcs_amount')) {
                $table->decimal('tcs_amount')->after('TCS_TAX')->default('0.00');
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
        Schema::table('orders', function (Blueprint $table) {
            //

        });
    }
};
