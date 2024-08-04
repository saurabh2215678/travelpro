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
        Schema::table('flags', function (Blueprint $table) {
            if (!Schema::hasColumn('flags', 'slug')) {
                $table->string('slug',255)->nullable()->after('name');
            }
            if (!Schema::hasColumn('flags', 'title')) {
                $table->string('title',255)->nullable()->after('slug');
            }
            if (!Schema::hasColumn('flags', 'brief')) {
                $table->string('brief',255)->nullable()->after('title');
            }
            if (!Schema::hasColumn('flags', 'identifier')) {
                $table->string('identifier',20)->nullable()->after('brief');
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
        Schema::table('flags', function (Blueprint $table) {
            //
        });
    }
};
