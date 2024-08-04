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
        Schema::create('form_data', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('form_id')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('subject', 100)->nullable();
            $table->mediumText('data')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->mediumText('user_agent')->nullable();
            $table->string('ipAddress', 30)->nullable();
            $table->boolean('is_captch')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data');
    }
};
