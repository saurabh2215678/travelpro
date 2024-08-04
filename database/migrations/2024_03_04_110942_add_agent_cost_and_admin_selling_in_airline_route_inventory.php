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
            if (!Schema::hasColumn('airline_route_inventory', 'p_agents')) {
                $table->text('p_agents')->nullable()->after('available_for');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'agent_adult_price')) {
                $table->decimal('agent_adult_price',15,2)->default(0)->after('airline_ticket_no');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'agent_child_price')) {
                $table->decimal('agent_child_price',15,2)->default(0)->after('agent_adult_price');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'agent_infant_price')) {
                $table->decimal('agent_infant_price',15,2)->default(0)->after('agent_child_price');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'admin_adult_price')) {
                $table->decimal('admin_adult_price',15,2)->default(0)->after('infant_price');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'admin_child_price')) {
                $table->decimal('admin_child_price',15,2)->default(0)->after('admin_adult_price');
            }
            if (!Schema::hasColumn('airline_route_inventory', 'admin_infant_price')) {
                $table->decimal('admin_infant_price',15,2)->default(0)->after('admin_child_price');
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
