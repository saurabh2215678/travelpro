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
        Schema::table('faqs', function (Blueprint $table) {
            $table->foreign(['sub_destination_id'], 'faq_sub_destination_id')->references(['id'])->on('destinations');
            $table->foreign(['destination_id'], 'faq_destination_id')->references(['id'])->on('destinations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropForeign('faq_sub_destination_id');
            $table->dropForeign('faq_destination_id');
        });
    }
};
