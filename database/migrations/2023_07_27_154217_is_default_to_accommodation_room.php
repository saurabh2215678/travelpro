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
        Schema::table('accommodation_room', function (Blueprint $table) {
            if (!Schema::hasColumn('accommodation_room', 'is_default')) {
                $table->tinyInteger('is_default')->nullable()->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accommodation_room', function (Blueprint $table) {
            //
        });
    }
};
