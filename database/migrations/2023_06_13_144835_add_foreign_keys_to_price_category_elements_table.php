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
        Schema::table('price_category_elements', function (Blueprint $table) {
            $table->foreign(['pc_id'], 'pc_id')->references(['id'])->on('price_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_category_elements', function (Blueprint $table) {
            $table->dropForeign('pc_id');
        });
    }
};
