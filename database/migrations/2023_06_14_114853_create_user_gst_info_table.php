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
        Schema::create('user_gst_info', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index('user_id');
            $table->string('gst_number', 100)->nullable();
            $table->string('gst_company', 100)->nullable();
            $table->string('gst_email', 100)->nullable();
            $table->string('gst_phone', 100)->nullable();
            $table->string('gst_address', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_gst_info');
    }
};
