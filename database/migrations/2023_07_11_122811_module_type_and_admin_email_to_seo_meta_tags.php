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
        Schema::table('seo_meta_tags', function (Blueprint $table) {
           if(!Schema::hasColumn('seo_meta_tags','module_type')) {
            $table->enum('module_type',['major', 'minor'])->nullable()->default('major')->after('agent_discount');
            }
            if(!Schema::hasColumn('seo_meta_tags', 'admin_email')) {
                $table->string('admin_email',100)->nullable()->after('module_type');
            }
            if(!Schema::hasColumn('seo_meta_tags', 'admin_phone')) {
                $table->string('admin_phone',100)->nullable()->after('admin_email');
            }
            if(!Schema::hasColumn('seo_meta_tags', 'online_booking')) {
                $table->tinyInteger('online_booking')->nullable()->default(0)->after('admin_phone');
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
        Schema::table('seo_meta_tags', function (Blueprint $table) {
            //
        });
    }
};
