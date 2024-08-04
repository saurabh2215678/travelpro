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
        Schema::table('order_amendments', function (Blueprint $table) {
            if (!Schema::hasColumn('order_amendments', 'cron_last_run')) {
                $table->dateTime('cron_last_run')->nullable()->after('status');
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
        Schema::table('order_amendments', function (Blueprint $table) {
            //
        });
    }
};
