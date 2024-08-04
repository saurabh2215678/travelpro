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
        Schema::table('airport_codes_master', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('airport_codes_master');
            if(!$doctrineTable->hasIndex('airport_codes_master_code_name_citycode_city_status_index')) {
                $table->index(['code', 'name', 'citycode', 'city', 'status']);
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
        Schema::table('airport_codes_master', function (Blueprint $table) {
            //
        });
    }
};
