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
        Schema::table('destination_flag', function (Blueprint $table) {
            if(!Schema::hasColumn('destination_flag', 'page_title')) {
                $table->string('page_title',255)->nullable()->after('name');
            }if(!Schema::hasColumn('destination_flag', 'page_brief')) {
                $table->text('page_brief')->nullable()->after('page_title');
            }if(!Schema::hasColumn('destination_flag', 'page_description')){
                $table->text('page_description')->nullable()->after('page_brief');
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
        Schema::table('destination_flag', function (Blueprint $table) {
            //
        });
    }
};
