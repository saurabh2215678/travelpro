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
           if (!Schema::hasColumn('bike_cities', 'discount_category_id')) {
               $table->Integer('discount_category_id')->nullable()->after('km_included');
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
