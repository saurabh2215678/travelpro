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
        Schema::table('form_elements', function (Blueprint $table) {
            $table->foreign(['form_id'], 'form_id')->references(['id'])->on('forms');
            $table->foreign(['form_id'], 'form_elements_ibfk_1')->references(['id'])->on('forms')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_elements', function (Blueprint $table) {
            $table->dropForeign('form_id');
            $table->dropForeign('form_elements_ibfk_1');
        });
    }
};
