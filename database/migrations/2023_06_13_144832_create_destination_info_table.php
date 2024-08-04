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
        Schema::create('destination_info', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('destination_id')->index('info_destination_id');
            $table->string('title');
            $table->integer('type')->nullable();
            $table->text('brief')->nullable();
            $table->boolean('featured')->nullable();
            $table->text('description');
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->smallInteger('sort_order')->default(0);
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
        Schema::dropIfExists('destination_info');
    }
};
