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
        Schema::table('package_to_settings', function (Blueprint $table) {
            $table->foreign(['package_id'], 'package_to_settings_ibfk_2')->references(['id'])->on('packages')->onUpdate('CASCADE');
            $table->foreign(['setting_id'], 'package_to_settings_ibfk_1')->references(['id'])->on('package_settings')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_to_settings', function (Blueprint $table) {
            $table->dropForeign('package_to_settings_ibfk_2');
            $table->dropForeign('package_to_settings_ibfk_1');
        });
    }
};
