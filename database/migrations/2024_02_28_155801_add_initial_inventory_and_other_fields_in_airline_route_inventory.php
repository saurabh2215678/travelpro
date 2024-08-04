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
        Schema::table('airline_route_inventory', function (Blueprint $table) {
            if (!Schema::hasColumn('airline_route_inventory', 'available_for')) {
                $table->string('available_for',100)->nullable()->after('fare_type');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'initial_inventory')) {
                $table->integer('initial_inventory')->default(0)->after('end_date');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'airline_ticket_no')) {
                $table->string('airline_ticket_no',100)->nullable()->after('pnr');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'remark')) {
                $table->text('remark')->nullable()->after('flight_class');
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
        Schema::table('airline_route_inventory', function (Blueprint $table) {
            //
        });
    }
};
