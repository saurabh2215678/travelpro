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
        Schema::table('email_templates', function (Blueprint $table) {
             if(!Schema::hasColumn('email_templates','manager_email')) {
                $table->tinyInteger('manager_email')->comment('0=No,1=Yes')->after('bcc_admin');
            }
            if(!Schema::hasColumn('email_templates','contact_email')) {
                $table->tinyInteger('contact_email')->comment('0=No,1=Yes')->after('manager_email');
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
        Schema::table('email_templates', function (Blueprint $table) {
            //
        });
    }
};
