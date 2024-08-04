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
            if (!Schema::hasColumn('accommodation_room', 'publish_price')) {
                $table->decimal('publish_price',15)->nullable()->after('base_price');
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
