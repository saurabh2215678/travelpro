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
        Schema::create('accommodation_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('accommodation_id')->index('additionalinfo_accommodation_id');
            $table->string('title');
            $table->text('brief')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->integer('sort_order');
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
        Schema::dropIfExists('accommodation_info');
    }
};
