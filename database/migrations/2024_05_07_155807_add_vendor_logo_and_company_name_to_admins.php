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
        Schema::table('admins', function (Blueprint $table) {
            if(!Schema::hasColumn('admins', 'vendor_logo')) {
                $table->string('vendor_logo',100)->nullable()->after('otp');
            }
            if(!Schema::hasColumn('admins', 'vendor_company_name')) {
                $table->string('vendor_company_name',255)->nullable()->after('vendor_logo');
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
        Schema::table('admins', function (Blueprint $table) {
            //
        });
    }
};
