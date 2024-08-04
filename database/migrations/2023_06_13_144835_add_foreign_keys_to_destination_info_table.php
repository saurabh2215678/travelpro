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
        Schema::table('destination_info', function (Blueprint $table) {
            $table->foreign(['destination_id'], 'info_destination_id')->references(['id'])->on('destinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('destination_info', function (Blueprint $table) {
            $table->dropForeign('info_destination_id');
        });
    }
};
