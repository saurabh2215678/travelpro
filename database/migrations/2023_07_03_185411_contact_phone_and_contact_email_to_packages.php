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
       Schema::table('packages', function (Blueprint $table) {

        if(!Schema::hasColumn('packages','place')) {
            $table->string('place',255)->nullable()->after('description');
        }
        if(!Schema::hasColumn('packages','additional_places')) {
            $table->string('additional_places',255)->after('place');
        } 
        if(!Schema::hasColumn('packages','address')) {
            $table->string('address',255)->after('additional_places');
        } 
        if(!Schema::hasColumn('packages',' contact_person')) {
            $table->string('contact_person',255)->after('address');
        }
        if(!Schema::hasColumn('packages','contact_phone')) {
            $table->string('contact_phone',20)->after('contact_person');
        }
        if(!Schema::hasColumn('packages','contact_email')) {
            $table->string('contact_email',100)->after('contact_phone');
        }
         if(!Schema::hasColumn('packages','booking_mode')) {
            $table->integer('booking_mode')->nullable()->comment('0=>Automatic booking mode,1=>Booking needs approval')->after('contact_email');
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
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
};
