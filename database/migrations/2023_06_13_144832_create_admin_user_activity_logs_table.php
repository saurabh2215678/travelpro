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
        Schema::create('admin_user_activity_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('user_name', 120)->nullable();
            $table->string('module', 60);
            $table->integer('module_id')->index('module_id');
            $table->string('module_desc');
            $table->mediumText('sub_module')->nullable();
            $table->integer('sub_module_id')->nullable()->index('sub_module_id');
            $table->string('sub_module_desc')->nullable();
            $table->mediumText('sub_sub_module')->nullable();
            $table->integer('sub_sub_module_id')->nullable()->index('sub_sub_module_id');
            $table->string('sub_sub_module_desc')->nullable();
            $table->mediumText('data_after_action')->nullable();
            $table->string('action', 120);
            $table->mediumText('user_agent');
            $table->string('ip_address', 39);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_activity_logs');
    }
};
