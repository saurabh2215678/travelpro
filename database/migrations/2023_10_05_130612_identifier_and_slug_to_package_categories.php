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
        Schema::table('package_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('package_categories', 'slug')) {
                $table->string('slug',255)->nullable()->after('title');
            }
            if (!Schema::hasColumn('package_categories', 'identifier')) {
                $table->string('identifier',20)->nullable()->after('slug');
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
        Schema::table('package_categories', function (Blueprint $table) {
            //
        });
    }
};
