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
        Schema::table('airline_route_segments', function (Blueprint $table) {
            if (!Schema::hasColumn('airline_route_segments', 'stops')) {
                $table->integer('stops')->default(0)->after('is_arrival_next_day');
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
        Schema::table('airline_route_segments', function (Blueprint $table) {
            //
        });
    }
};
