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
        Schema::table('website_settings', function (Blueprint $table) {
            
            if (!Schema::hasColumn('website_settings', 'description')) {
                $table->text('description')->nullable()->default(null)->after('name');
            }
            if (!Schema::hasColumn('website_settings', 'default_value')) {
                $table->text('default_value')->nullable()->default(null)->after('old_value');
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
        Schema::table('website_settings', function (Blueprint $table) {
            //
        });
    }
};
