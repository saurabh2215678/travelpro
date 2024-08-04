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
        Schema::table('orders_traveller', function (Blueprint $table) {
            if (!Schema::hasColumn('orders_traveller', 'pnrDetails')) {
                $table->mediumText('pnrDetails')->nullable()->after('serial_no');
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
        Schema::table('orders_traveller', function (Blueprint $table) {
            //
        });
    }
};
