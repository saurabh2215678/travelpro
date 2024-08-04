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
        Schema::table('book_now_enquiries', function (Blueprint $table) {
            $table->foreign(['package_id'], 'package_id')->references(['id'])->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_now_enquiries', function (Blueprint $table) {
            $table->dropForeign('package_id');
        });
    }
};
