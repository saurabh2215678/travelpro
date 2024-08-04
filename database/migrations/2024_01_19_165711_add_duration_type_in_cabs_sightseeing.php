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
        Schema::table('cabs_sightseeing', function (Blueprint $table) {
            if (!Schema::hasColumn('cabs_sightseeing', 'duration_type')) {
                $table->string('duration_type',10)->default('days')->after('destination');
            }
            if (!Schema::hasColumn('cabs_sightseeing', 'duration_value')) {
                $table->integer('duration_value')->default(0)->after('duration_type');
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
        Schema::table('cabs_sightseeing', function (Blueprint $table) {
            //
        });
    }
};
