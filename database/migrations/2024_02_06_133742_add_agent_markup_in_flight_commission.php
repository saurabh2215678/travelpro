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
        Schema::table('flight_commission', function (Blueprint $table) {
            if (!Schema::hasColumn('flight_commission', 'agent_markup_type')) {
                $table->string('agent_markup_type', 10)->after('online_markup_value');
            }
            if (!Schema::hasColumn('flight_commission', 'agent_markup_value')) {
                $table->string('agent_markup_value',20)->nullable()->after('agent_markup_type');
            }

            if (!Schema::hasColumn('flight_commission', 'agent_commission_type')) {
                $table->string('agent_commission_type', 10)->after('online_commission_value');
            }
            if (!Schema::hasColumn('flight_commission', 'agent_commission_value')) {
                $table->string('agent_commission_value',20)->nullable()->after('agent_commission_type');
            }

            if (!Schema::hasColumn('flight_commission', 'agent_discount_type')) {
                $table->string('agent_discount_type', 10)->after('online_discount_value');
            }
            if (!Schema::hasColumn('flight_commission', 'agent_discount_value')) {
                $table->string('agent_discount_value',20)->nullable()->after('agent_discount_type');
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
        Schema::table('flight_commission', function (Blueprint $table) {
            //
        });
    }
};
