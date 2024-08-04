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
           if (!Schema::hasColumn('orders', 'discount_coupon')) {
                $table->decimal('discount_coupon',10,2)->nullable()->default(0.00)->after('discount_type');
            }
            if (!Schema::hasColumn('orders', 'discount_coupon_details')) {
                $table->text('discount_coupon_details')->after('discount_coupon');
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
