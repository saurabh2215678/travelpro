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
        Schema::table('cms_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('cms_pages', 'cms_type')) {
                $table->enum('cms_type',['page','section'])->nullable(false)->default('page')->after('type');
            }
            if (Schema::hasColumn('cms_pages', 'slug')) {
                $table->string('slug',255)->nullable(false)->unique()->change();
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
        Schema::table('cms_pages', function (Blueprint $table) {
            //
        });
    }
};
