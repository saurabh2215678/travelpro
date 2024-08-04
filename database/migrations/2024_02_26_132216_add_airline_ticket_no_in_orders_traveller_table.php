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
            if (!Schema::hasColumn('orders_traveller', 'airline_ticket_no')) {
                $table->mediumText('airline_ticket_no')->nullable()->after('pnrDetails');
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
