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
        Schema::table('destinations', function (Blueprint $table) {
            if (!Schema::hasColumn('destinations', 'hotels_pages_title')) {
                $table->mediumText('hotels_pages_title')->nullable()->after('activity_meta_description')->default(null);
            }
            if (!Schema::hasColumn('destinations', 'hotels_pages_description')) {
                $table->longText('hotels_pages_description')->nullable()->after('hotels_pages_title')->default(null);
            }
            if (!Schema::hasColumn('destinations', 'hotels_meta_title')) {
                $table->string('hotels_meta_title',650)->nullable()->after('hotels_pages_description')->default(null);
            }
            if (!Schema::hasColumn('destinations', 'hotels_meta_keyword')) {
                $table->string('hotels_meta_keyword',650)->nullable()->after('hotels_meta_title')->default(null);
            }
            if (!Schema::hasColumn('destinations', 'hotels_meta_description')) {
                $table->string('hotels_meta_description',650)->nullable()->after('hotels_meta_keyword')->default(null);
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
        Schema::table('destinations', function (Blueprint $table) {
            //
        });
    }
};
