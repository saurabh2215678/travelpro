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
        Schema::table('bike_cities', function (Blueprint $table) {
            if (!Schema::hasColumn('bike_cities', 'is_deleted')) {
                $table->tinyInteger('is_deleted')->nullable()->after('status');
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
        Schema::table('bike_cities', function (Blueprint $table) {
            //
        });
    }
};
