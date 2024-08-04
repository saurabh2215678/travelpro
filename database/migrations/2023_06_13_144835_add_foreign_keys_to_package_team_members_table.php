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
        Schema::table('package_team_members', function (Blueprint $table) {
            $table->foreign(['package_id'], 'pt_package_id')->references(['id'])->on('packages');
            $table->foreign(['member_id'], 'member_id')->references(['id'])->on('team_management');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_team_members', function (Blueprint $table) {
            $table->dropForeign('pt_package_id');
            $table->dropForeign('member_id');
        });
    }
};
