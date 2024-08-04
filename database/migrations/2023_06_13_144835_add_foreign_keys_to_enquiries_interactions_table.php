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
        Schema::table('enquiries_interactions', function (Blueprint $table) {
            $table->foreign(['enquiry_id'], 'enquiries_interactions_ibfk_1')->references(['id'])->on('enquiries')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enquiries_interactions', function (Blueprint $table) {
            $table->dropForeign('enquiries_interactions_ibfk_1');
        });
    }
};
