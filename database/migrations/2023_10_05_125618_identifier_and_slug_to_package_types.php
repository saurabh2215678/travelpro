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
        Schema::table('package_types', function (Blueprint $table) {
            if (!Schema::hasColumn('package_types', 'slug')) {
                $table->string('slug',255)->nullable()->after('package_type');
            }
            if (!Schema::hasColumn('package_types', 'identifier')) {
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
        Schema::table('package_types', function (Blueprint $table) {
            //
        });
    }
};
