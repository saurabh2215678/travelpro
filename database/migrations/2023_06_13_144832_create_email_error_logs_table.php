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
        Schema::create('email_error_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('msg_subject');
            $table->text('msg_body');
            $table->text('attachments');
            $table->string('msg_from');
            $table->string('msg_to');
            $table->string('module_name');
            $table->string('added_by', 50)->nullable();
            $table->string('ip');
            $table->integer('record_id');
            $table->string('status');
            $table->text('error_message');
            $table->string('type');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_error_logs');
    }
};
