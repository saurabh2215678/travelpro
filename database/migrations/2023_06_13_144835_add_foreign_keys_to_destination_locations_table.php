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
        Schema::table('destination_locations', function (Blueprint $table) {
            $table->foreign(['destination_id'], 'destination_locations_ibfk_1')->references(['id'])->on('destinations')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destination_locations', function (Blueprint $table) {
            $table->dropForeign('destination_locations_ibfk_1');
        });
    }
};
