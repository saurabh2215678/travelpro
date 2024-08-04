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
        Schema::table('cab_route', function (Blueprint $table) {
            if (!Schema::hasColumn('cab_route', 'publish_price')) {
                $table->decimal('publish_price',15,2)->after('image')->default('0.00');
            }
            if (!Schema::hasColumn('cab_route', 'search_price')) {
                $table->decimal('search_price',15,2)->after('publish_price')->default('0.00');
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
        Schema::table('cab_route_price', function (Blueprint $table) {
            //
        });
    }
};
