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
        Schema::table('itenary_tags', function (Blueprint $table) {
            $table->foreign(['tag_id'], 'itenary_tag_id')->references(['id'])->on('tags');
            $table->foreign(['itenary_id'], 'itenaries_id')->references(['id'])->on('itenaries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itenary_tags', function (Blueprint $table) {
            $table->dropForeign('itenary_tag_id');
            $table->dropForeign('itenaries_id');
        });
    }
};
