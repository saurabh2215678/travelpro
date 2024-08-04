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
        Schema::table('airline_dc', function (Blueprint $table) {
            if (!Schema::hasColumn('airline_dc', 'domestic_agent_type')) {
                $table->string('domestic_agent_type', 10)->after('domestic_online_value');
            }
            if (!Schema::hasColumn('airline_dc', 'domestic_agent_value')) {
                $table->string('domestic_agent_value',20)->nullable()->after('domestic_agent_type');
            }

            if (!Schema::hasColumn('airline_dc', 'international_agent_type')) {
                $table->string('international_agent_type', 10)->after('international_online_value');
            }
            if (!Schema::hasColumn('airline_dc', 'international_agent_value')) {
                $table->string('international_agent_value',20)->nullable()->after('international_agent_type');
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
        Schema::table('airline_dc', function (Blueprint $table) {
            //
        });
    }
};
