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
        Schema::table('user_agent_info', function (Blueprint $table) {
            if (!Schema::hasColumn('user_agent_info', 'is_allowed_offline_flight_inventory')) {
                $table->integer('is_allowed_offline_flight_inventory')->default(0)->after('query');
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
        Schema::table('user_agent_info', function (Blueprint $table) {
            //
        });
    }
};
