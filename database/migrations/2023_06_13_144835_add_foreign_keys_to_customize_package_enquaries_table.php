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
        Schema::table('customize_package_enquaries', function (Blueprint $table) {
            $table->foreign(['package_id'], 'pack_package_id')->references(['id'])->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customize_package_enquaries', function (Blueprint $table) {
            $table->dropForeign('pack_package_id');
        });
    }
};
