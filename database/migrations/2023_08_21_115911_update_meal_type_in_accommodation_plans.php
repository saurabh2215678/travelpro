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
        Schema::table('accommodation_plans', function (Blueprint $table) {
            if (Schema::hasColumn('accommodation_plans', 'meal_type')) {
                $table->string('meal_type',100)->nullable()->change();
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
        Schema::table('accommodation_plans', function (Blueprint $table) {
            //
        });
    }
};
