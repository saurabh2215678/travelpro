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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('user_id')->index('user_id');
            $table->integer('row_id');
            $table->string('function_name', 200);
            $table->string('action_table');
            $table->string('action_type');
            $table->text('action_description');
            $table->text('description');
            $table->string('ip_address', 16);
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
