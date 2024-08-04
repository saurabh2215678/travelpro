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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 100);
            $table->string('slug', 100);
            $table->string('subject');
            $table->mediumText('content');
            $table->enum('email_type', ['customer', 'admin']);
            $table->tinyInteger('bcc_admin')->comment('0=No,1=Yes');
            $table->integer('status');
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
        Schema::dropIfExists('email_templates');
    }
};
