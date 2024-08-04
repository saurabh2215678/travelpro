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
        Schema::table('orders_status_history', function (Blueprint $table) {
            if (!Schema::hasColumn('orders_status_history', 'created_by_name')) {
                $table->string('created_by_name')->nullable()->after('created_by');
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
        Schema::table('orders_status_history', function (Blueprint $table) {
            //
        });
    }
};
