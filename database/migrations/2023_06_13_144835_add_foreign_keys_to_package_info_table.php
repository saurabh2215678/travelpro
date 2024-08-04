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
        Schema::table('package_info', function (Blueprint $table) {
            $table->foreign(['package_id'], 'package_info_ibfk_1')->references(['id'])->on('packages')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_info', function (Blueprint $table) {
            $table->dropForeign('package_info_ibfk_1');
        });
    }
};
