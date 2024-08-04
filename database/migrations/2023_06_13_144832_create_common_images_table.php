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
        Schema::create('common_images', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('tbl_id')->nullable();
            $table->string('category', 25)->nullable();
            $table->string('tbl_name')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->text('link')->nullable();
            $table->integer('sort_order')->nullable()->default(0);
            $table->integer('is_default')->nullable();
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
        Schema::dropIfExists('common_images');
    }
};
