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
        Schema::create('banner_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('banner_id')->nullable()->index('banner_id');
            $table->string('image_name')->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('link_text_1')->nullable();
            $table->string('link_1')->nullable();
            $table->string('link_text_2')->nullable();
            $table->string('link_2')->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->integer('status')->nullable()->default(1);
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
        Schema::dropIfExists('banner_images');
    }
};
