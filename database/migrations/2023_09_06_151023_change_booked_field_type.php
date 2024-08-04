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
        Schema::table('bike_city_inventory', function (Blueprint $table) {
            if (Schema::hasColumn('bike_city_inventory', 'booked')) {
                $table->integer('booked')->default(0)->change();
            }
        });

        Schema::table('cab_route_inventory', function (Blueprint $table) {
            if (Schema::hasColumn('cab_route_inventory', 'booked')) {
                $table->integer('booked')->default(0)->change();
            }
        });

        Schema::table('accommodation_inventory', function (Blueprint $table) {
            if (Schema::hasColumn('accommodation_inventory', 'booked')) {
                $table->integer('booked')->default(0)->change();
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
        Schema::table('bike_city_inventory', function (Blueprint $table) {
            //
        });
    }
};
