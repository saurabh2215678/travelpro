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
        Schema::create('api_call_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('api_name')->nullable();
            $table->mediumText('api_url')->nullable();
            $table->string('request_method', 50)->nullable();
            $table->mediumText('description')->nullable();
            $table->dateTime('call_date');
            $table->mediumText('request_json')->nullable();
            $table->longText('response_json')->nullable();
            $table->integer('user_id');
            $table->string('session_id')->nullable();
            $table->string('ip_address', 20)->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_call_log');
    }
};
