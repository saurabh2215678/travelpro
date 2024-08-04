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
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('mail_gateway', 10);
            $table->string('sender_name', 50)->nullable();
            $table->string('sender_mail', 50)->nullable();
            $table->string('from_name', 100)->nullable();
            $table->string('from_mail', 50)->nullable();
            $table->string('mail_host', 100)->nullable();
            $table->integer('mail_port')->nullable();
            $table->string('mail_username', 100)->nullable();
            $table->mediumText('mail_password')->nullable();
            $table->string('mail_encryption', 100)->nullable();
            $table->string('email_charset', 50)->nullable();
            $table->tinyInteger('is_queue')->default(0);
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
        Schema::dropIfExists('smtp_settings');
    }
};
