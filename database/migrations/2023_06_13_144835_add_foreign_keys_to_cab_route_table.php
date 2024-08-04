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
            $table->foreign(['cab_route_group_id'], 'cab_route_ibfk_3')->references(['id'])->on('cab_route_group')->onUpdate('CASCADE');
            $table->foreign(['destination'], 'cab_route_ibfk_2')->references(['id'])->on('cab_cities')->onUpdate('CASCADE');
            $table->foreign(['origin'], 'cab_route_ibfk_1')->references(['id'])->on('cab_cities')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cab_route', function (Blueprint $table) {
            $table->dropForeign('cab_route_ibfk_3');
            $table->dropForeign('cab_route_ibfk_2');
            $table->dropForeign('cab_route_ibfk_1');
        });
    }
};
