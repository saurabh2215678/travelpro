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
        Schema::create('banner_image_gallery', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->mediumText('brief')->nullable();
            $table->string('page', 50)->nullable();
            $table->string('image')->nullable();
            $table->string('device_type', 20)->nullable();
            $table->string('link')->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('banner_image_gallery');
    }
};
