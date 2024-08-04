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
        Schema::table('package_price_info', function (Blueprint $table) {
            if (!Schema::hasColumn('package_price_info', 'booking_price_b2b')) {
                $table->decimal('booking_price_b2b',15,2)->nullable()->after('is_book_without_payment');
            }
            if (!Schema::hasColumn('package_price_info', 'is_partial_amount_b2b')) {
                $table->boolean('is_partial_amount_b2b')->nullable()->default(0)->after('booking_price_b2b');
            }
            if (!Schema::hasColumn('package_price_info', 'is_book_without_payment_b2b')) {
                $table->boolean('is_book_without_payment_b2b')->nullable()->default(0)->after('is_partial_amount_b2b');
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
        Schema::table('package_price_info', function (Blueprint $table) {
            //
        });
    }
};
