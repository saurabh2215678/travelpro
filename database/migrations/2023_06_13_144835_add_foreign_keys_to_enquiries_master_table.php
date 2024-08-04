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
        Schema::table('enquiries_master', function (Blueprint $table) {
            $table->foreign(['group_id'], 'enquiries_master_ibfk_1')->references(['id'])->on('enquiries_master_group')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquiries_master', function (Blueprint $table) {
            $table->dropForeign('enquiries_master_ibfk_1');
        });
    }
};
